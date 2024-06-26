<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUs = AboutUs::get();
        if ($aboutUs->count() == 0) {
            return response()->json([
                'status' => true,
                'Massage' => 'Nothing About Us Now !'
            ], 200);
        }
        return response($aboutUs);
    }
    public function show($id)
    {
        $aboutUs = AboutUs::where('id', $id)->get();
        if ($aboutUs->count() == 0) {
            return response()->json([
                'status' => true,
                'Massage' => 'Nothing About Us Now !'
            ], 200);
        }
        return response($aboutUs);
    }
    public function create(Request $req)
    {
        $validateAboutUs = Validator::make(
            $req->all(),
            [
                'image'         => 'required|mimes:png,jpg',
                'whoWeAre'      => 'required',
                'ourVission'    => 'required',
                'ourMission'    => 'required',
                'location'      => 'required',
            ]
        );
        if ($validateAboutUs->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateAboutUs->errors()
            ], 401);
        }
        $image = $req->image;
        if ($image) {
            $file = $req->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = "Image" .  time() . '.' . $extention;
            $path =  url('/images/aboutus/' . $filename);
        }
        $aboutUs = AboutUs::create([
            'image' => $path,
            'whoWeAre' => $req->whoWeAre,
            'ourVission' => $req->ourVission,
            'ourMission' => $req->ourMission,
            'location' => $req->location,
        ]);
        $file->move('images/aboutus/', $filename);
        return response()->json([
            'status' => true,
            'message' => 'About Us Created Successfully',
        ], 200);
    }
    public function update(Request $req, $id)
    {
        $validateAboutUS = Validator::make(
            $req->all(),
            [
                'image' => 'required|mimes:png,jpg',
                'whoWeAre' => 'required',
                'ourVission' => 'required',
                'ourMission' => 'required',
                'location' => 'required',
            ]
        );
        if ($validateAboutUS->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateAboutUS->errors()
            ], 401);
        }
        $aboutUs = AboutUs::findOrFail($id);
        $name = $aboutUs->image;
        $nameImageUpdate = ltrim($name, url('/images/aboutus'));
        $imagess = $req->file("image");
        if ($imagess) {
            if ($nameImageUpdate !== null) {
                unlink(public_path("images/aboutus/") . $nameImageUpdate);
            }
            $image = $req->file("image");
            $nameOfNewImage = "Image" . time() . "." . $image->getClientOriginalExtension();
            $path =  url('/images/aboutus/' . $nameOfNewImage);
        }
        $aboutUs->update([
            'image' => $path,
            'whoWeAre' => $req->whoWeAre,
            'ourVission' => $req->ourVission,
            'ourMission' => $req->ourMission,
            'location' => $req->location,
        ]);
        $image->move(public_path("images/aboutus/"), $nameOfNewImage);
        return response()->json([
            'status' => true,
            'message' => 'About Us Updated Successfully',
        ], 200);
    }
    public function destroy($id)
    {
        $aboutUs = AboutUs::findOrFail($id);
        $imagePath =  $aboutUs->image;
        $nameImageDelete = ltrim($imagePath, url('/images/aboutus'));
        if ($nameImageDelete) {
            unlink(public_path("images/aboutus/") . $nameImageDelete);
        }
        $aboutUs->delete();
        if ($aboutUs) {
            return response()->json([
                'status' => true,
                'message' => 'About Us Deleted Successfully',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Category not Deleted',
        ], 401);
    }
}
