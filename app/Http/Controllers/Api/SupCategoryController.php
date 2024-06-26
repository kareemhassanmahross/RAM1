<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\image;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('product')->get();
        if ($subCategories->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Sub Categories Now'
            ], 200);
        }
        return response($subCategories);
    }
    public function show($id)
    {
        $subCategory = SubCategory::where('id', $id)->get();
        if ($subCategory->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Sub Categories Now'
            ], 401);
        }
        return response($subCategory);
    }
    public function create(Request $req)
    {
        $validateSubCategory = Validator::make(
            $req->all(),
            [
                "name" => 'required',
                "desctription" => 'required',
                'image' => 'required|mimes:png,jpg',
                'category_id' => 'required'
            ]
        );
        if ($validateSubCategory->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateSubCategory->errors()
            ], 401);
        }
        $image = $req->image;
        if ($image) {
            $file = $req->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = "Image" .  time() . '.' . $extention;
            $path =  url('/images/SubCategory/' . $filename);
        }
        $category =  SubCategory::create([
            "name" => $req->name,
            "desctription" => $req->desctription,
            'image' => $path,
            'category_id' => $req->category_id,
        ]);
        $file->move('images/SubCategory/', $filename);
        return response()->json([
            'status' => true,
            'message' => 'Sub Category Created Successfully',
        ], 200);
    }
    public function update(Request $req, $id)
    {
        $validateSubCategory = Validator::make(
            $req->all(),
            [
                "name" => 'required',
                "desctription" => 'required',
                'image' => 'required|mimes:png,jpg',
                'category_id' => 'required'
            ]
        );
        if ($validateSubCategory->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateSubCategory->errors()
            ], 401);
        }
        $category = SubCategory::findOrFail($id);
        $name = $category->image;
        $nameImageUpdate = ltrim($name, url('/images/SubCategory'));
        $imagess = $req->file("image");
        if ($imagess) {
            if ($nameImageUpdate !== null) {
                unlink(public_path("images/SubCategory/") . $nameImageUpdate);
            }
            $image = $req->file("image");
            $nameOfNewImage = "Image" . time() . "." . $image->getClientOriginalExtension();
            $path =  url('/images/SubCategory/' . $nameOfNewImage);
        }
        $category->update([
            "name" => $req->name,
            "desctription" => $req->desctription,
            'image' => $path,
            'category_id' => $req->category_id,
        ]);
        $image->move(public_path("images/SubCategory/"), $nameOfNewImage);
        return response()->json([
            'status' => true,
            'message' => 'Sub Category Updated Successfully',
        ], 200);
    }
    public function destroy($id)
    {
        $SubCategory = SubCategory::findOrFail($id);
        $imagePath =  $SubCategory->image;
        $nameImageDelete = ltrim($imagePath, url('/images/SubCategory'));
        if ($nameImageDelete) {
            unlink(public_path("images/SubCategory/") . $nameImageDelete);
        }
        $SubCategory->delete();
        if ($SubCategory) {
            return response()->json([
                'status' => true,
                'message' => 'SubCategory Deleted Successfully',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'SubCategory not Deleted',
        ], 401);
    }
}
