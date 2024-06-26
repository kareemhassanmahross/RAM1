<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subCategory')->get();
        if ($categories->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Categories Now'
            ], 200);
        }
        return response($categories);
    }

    public function show($id)
    {
        $category = Category::where('id', $id)->with('subCategory')->get();
        if ($category->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Categories Now'
            ], 401);
        }
        return response($category);
    }
    public function create(Request $req)
    {
        $validateCategory = Validator::make(
            $req->all(),
            [
                "name" => 'required',
                "desctription" => 'required',
                'image' => 'required|mimes:png,jpg',
            ]
        );
        if ($validateCategory->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateCategory->errors()
            ], 401);
        }
        $image = $req->image;
        if ($image) {
            $file = $req->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = "Image" .  time() . '.' . $extention;
            $path =  url('/images/category/' . $filename);
        }
        $category =  Category::create([
            "name" => $req->name,
            "desctription" => $req->desctription,
            'image' => $path,
        ]);
        $file->move('images/category/', $filename);
        return response()->json([
            'status' => true,
            'message' => 'Category Created Successfully',
        ], 200);
    }
    public function update(Request $req, $id)
    {
        $validateCategory = Validator::make(
            $req->all(),
            [
                "name" => 'required',
                "desctription" => 'required',
                'image' => 'required|mimes:png,jpg',
            ]
        );
        if ($validateCategory->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateCategory->errors()
            ], 401);
        }
        $category = Category::findOrFail($id);
        $name = $category->image;
        $nameImageUpdate = ltrim($name, url('/images/category'));
        $imagess = $req->file("image");
        if ($imagess) {
            if ($nameImageUpdate !== null) {
                unlink(public_path("images/category/") . $nameImageUpdate);
            }
            $image = $req->file("image");
            $nameOfNewImage = "Image" . time() . "." . $image->getClientOriginalExtension();
            $path =  url('/images/category/' . $nameOfNewImage);
        }
        $category->update([
            "name" => $req->name,
            "desctription" => $req->desctription,
            'image' => $path,
        ]);
        $image->move(public_path("images/category/"), $nameOfNewImage);
        return response()->json([
            'status' => true,
            'message' => 'Category Updated Successfully',
        ], 200);
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $imagePath =  $category->image;
        $nameImageDelete = ltrim($imagePath, url('/images/category'));
        if ($nameImageDelete) {
            unlink(public_path("images/category/") . $nameImageDelete);
        }
        $category->delete();
        if ($category) {
            return response()->json([
                'status' => true,
                'message' => 'Category Deleted Successfully',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Category not Deleted',
        ], 401);
    }
}
