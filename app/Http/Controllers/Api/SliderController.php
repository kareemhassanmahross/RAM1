<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $slider = Slider::get();
        if ($slider->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Slider Now'
            ], 200);
        }
        return response($slider);
    }
    public function show($id)
    {
        $slider = Slider::where('id', $id)->get();
        if ($slider->count() == 0) {
            return response()->json([
                'status' => true,
                'message' => 'Nothing Slider Now'
            ], 200);
        }
        return response($slider);
    }

    public function create(Request $req)
    {
        $validateSlider = Validator::make(
            $req->all(),
            [
                "name" => 'required',
                "description" => 'required',
                'image' => 'required|mimes:png,jpg',
            ]
        );
        if ($validateSlider->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateSlider->errors()
            ], 401);
        }
        $image = $req->image;
        if ($image) {
            $file = $req->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = "Image" .  time() . '.' . $extention;
            $path =  url('/images/slider/' . $filename);
        }
        $category =  Slider::create([
            "name" => $req->name,
            "description" => $req->description,
            'image' => $path,
        ]);
        $file->move('images/slider/', $filename);
        return response()->json([
            'status' => true,
            'message' => 'Slider Created Successfully',
        ], 200);
    }
    public function update(Request $req, $id)
    {
        $validateCategory = Validator::make(
            $req->all(),
            [
                "name" => 'required',
                "description" => 'required',
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
        $slider = Slider::findOrFail($id);
        $name = $slider->image;
        $nameImageUpdate = ltrim($name, url('/images/slider'));
        $imagess = $req->file("image");
        if ($imagess) {
            if ($nameImageUpdate !== null) {
                unlink(public_path("images/slider/") . $nameImageUpdate);
            }
            $image = $req->file("image");
            $nameOfNewImage = "Image" . time() . "." . $image->getClientOriginalExtension();
            $path =  url('/images/slider/' . $nameOfNewImage);
        }
        $slider->update([
            "name" => $req->name,
            "description" => $req->description,
            'image' => $path,
        ]);
        $image->move(public_path("images/slider/"), $nameOfNewImage);
        return response()->json([
            'status' => true,
            'message' => 'Slider Updated Successfully',
        ], 200);
    }
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $imagePath =  $slider->image;
        $nameImageDelete = ltrim($imagePath, url('/images/slider'));
        if ($nameImageDelete) {
            unlink(public_path("images/slider/") . $nameImageDelete);
        }
        $slider->delete();
        if ($slider) {
            return response()->json([
                'status' => true,
                'message' => 'Slider Deleted Successfully',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Slider not Deleted',
        ], 401);
    }
}
