<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ImagesController extends Controller
{
    public function index()
    {
        $images = image::get();
        if ($images->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Images Now'
            ], 200);
        }
        return response($images);
    }
    public function show($id)
    {
        $image = image::where('id', $id)->get();
        // dd($image);
        if ($image->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Images Now'
            ], 200);
        }
        return response($image);
    }
    public function create(Request $req)
    {
        $ProductImage = Validator::make(
            $req->all(),
            [
                'image' => 'required|mimes:png,jpg',
                'product_id' => 'nullable',
            ]
        );
        if ($ProductImage->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $ProductImage->errors()
            ], 401);
        }
        $image = $req->image;
        if ($image) {
            $file = $req->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = "Image" .  time() . '.' . $extention;
            $path =  url('/images/products/' . $filename);
        }
        $imageCreate = image::create([
            'image' => $path,
            'product_id' => $req->product_id,
        ]);
        $file->move('images/products/', $filename);
        return response()->json([
            'status' => true,
            'message' => 'Image Products Created Successfully',
        ], 200);
    }
    public function update(Request $req, $id)
    {
        $ProductImage = Validator::make(
            $req->all(),
            [
                'image' => 'required|mimes:png,jpg',
                'product_id' => 'nullable',
            ]
        );
        if ($ProductImage->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $ProductImage->errors()
            ], 401);
        }
        $imageCreate = image::findOrFail($id);
        $name = $imageCreate->image;
        $nameImageUpdate = ltrim($name, url('/images/products'));
        $imagess = $req->file("image");
        if ($imagess) {
            if ($nameImageUpdate !== null) {
                unlink(public_path("images/products/") . $nameImageUpdate);
            }
            $image = $req->file("image");
            $nameOfNewImage = "Image" . time() . "." . $image->getClientOriginalExtension();
            $image->move(public_path("images/products/"), $nameOfNewImage);
            $path =  url('/images/products/' . $nameOfNewImage);
        }
        $imageCreate->update([
            'image' => $path,
            'product_id' => $req->product_id,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Image Update Successfully',
        ], 200);
    }
    public function destroy($id)
    {
        $imageDelete = image::findOrFail($id);
        $imagePath =  $imageDelete->image;
        $nameImageUpdate = ltrim($imagePath, url('/images/products'));
        if ($nameImageUpdate) {
            unlink(public_path("images/products/") . $nameImageUpdate);
        }
        $imageDelete->delete();
        return response()->json([
            'status' => true,
            'message' => 'Image Deleted Successfully',
        ], 200);
    }
}