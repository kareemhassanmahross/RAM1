<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->get();
        if ($products->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Products Now'
            ], 200);
        }
        return response($products);
    }
    public function show($id)
    {
        $product = Product::with('images')->where('id', $id)->get();
        if ($product->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Sub Categories Now'
            ], 200);
        }
        return response($product);
    }
    public function create(Request $req)
    {
        $validateProduct = Validator::make(
            $req->all(),
            [
                'name' => 'required',
                'amount' => 'required',
                'description' => 'required',
                'price' => 'required',
                'sub_category_id' => 'nullable',
            ]
        );
        if ($validateProduct->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateProduct->errors()
            ], 401);
        }

        // dd($imageName);
        $product = Product::create([
            'name' => $req->name,
            'amount' =>  $req->amount,
            'description' => $req->description,
            'price' => $req->price,
            'sub_category_id' => $req->sub_category_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product Created Successfully',
        ], 200);
    }
    public function update(Request $req, $id)
    {
        $validateProduct = Validator::make(
            $req->all(),
            [
                'name' => 'required',
                'amount' => 'required',
                'description' => 'required',
                'price' => 'required',
                'sub_category_id' => 'nullable',
            ]
        );
        if ($validateProduct->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateProduct->errors()
            ], 401);
        }
        $product = Product::findOrFail($id);
        $product->update([
            'name' => $req->name,
            'amount' =>  $req->amount,
            'description' => $req->description,
            'price' => $req->price,
            'sub_category_id' => $req->sub_category_id,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Product Updated Successfully',
        ], 200);
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product) {
            $deleteImageProductImages = image::select('image')->where("product_id", $id)->get();
            if ($deleteImageProductImages->count() != 0) {
                foreach ($deleteImageProductImages as $image) {
                    $imagePath =  $image->image;
                    $nameImageUpdate = ltrim($imagePath, url('/images/products'));
                    if ($nameImageUpdate) {
                        unlink(public_path("images/products/") . $nameImageUpdate);
                    }
                }
            }
            $product->delete();
            return response()->json([
                'status' => true,
                'message' => 'Product Deleted Successfully',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Product not Deleted',
        ], 401);
    }
}