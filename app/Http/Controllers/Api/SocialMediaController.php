<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SocialMediaController extends Controller
{
    public function index()
    {
        $socialMedia = SocialMedia::get();
        if ($socialMedia->count() == 0) {
            return response()->json([
                'status' => true,
                'Massage' => 'Nothing Social Media Now !'
            ], 200);
        }
        return response($socialMedia);
    }
    public function show($id)
    {
        $socialMedia = SocialMedia::where('id', $id)->get();
        if ($socialMedia->count() == 0) {
            return response()->json([
                'status' => true,
                'Massage' => 'Nothing Social Media Now !'
            ], 200);
        }
        return response($socialMedia);
    }
    public function create(Request $req)
    {
        $validateSocialMedia = Validator::make(
            $req->all(),
            [
                'facebook' => 'required',
                'instgram' => 'required',
                'youtupe' => 'required',
                'linkedin' => 'required',
            ]
        );
        if ($validateSocialMedia->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateSocialMedia->errors()
            ], 401);
        }
        $socialMedia = SocialMedia::create([
            'facebook' => $req->facebook,
            'instgram' => $req->instgram,
            'youtupe' => $req->youtupe,
            'linkedin' => $req->linkedin,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Links Social Media Created Successfully',
        ], 200);
    }
    public function update(Request $req, $id)
    {
        $validateSocialMedia = Validator::make(
            $req->all(),
            [
                'facebook' => 'required',
                'instgram' => 'required',
                'youtupe' => 'required',
                'linkedin' => 'required',
            ]
        );
        if ($validateSocialMedia->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateSocialMedia->errors()
            ], 401);
        }
        $socialMedia = SocialMedia::findOrFail($id);
        $socialMedia->update([
            'facebook' => $req->facebook,
            'instgram' => $req->instgram,
            'youtupe' => $req->youtupe,
            'linkedin' => $req->linkedin,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Links Social Media Updated Successfully',
        ], 200);
    }
    public function destroy($id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        if (isset($socialMedia)) {
            $socialMedia->delete();
            return response()->json([
                'status' => true,
                'massage' => 'Links Social Media Deleted Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'massage' => 'Links Social Media Deleted Faild'
            ], 400);
        }
    }
}
