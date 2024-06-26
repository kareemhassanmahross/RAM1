<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function index()
    {
        $contactUs = ContactUs::get();
        if ($contactUs->count() == 0) {
            return response()->json([
                'status' => true,
                'Massage' => 'Nothing Contact Us Now !'
            ], 200);
        }
        return response($contactUs);
    }
    public function show($id)
    {
        $contactUs = ContactUs::where('id', $id)->get();
        if ($contactUs->count() == 0) {
            return response()->json([
                'status' => true,
                'Massage' => 'Nothing Contact Us Now !'
            ], 200);
        }
        return response($contactUs);
    }
    public function create(Request $req)
    {
        $validatecontactUs = Validator::make(
            $req->all(),
            [
                'email' => 'required',
                'phone' => 'required',
                'whatsApp' => 'required',
                'tel' => 'required',
            ]
        );
        if ($validatecontactUs->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatecontactUs->errors()
            ], 401);
        }
        $contactUs = ContactUs::create([
            'email' => $req->email,
            'phone' => $req->phone,
            'whatsApp' => $req->whatsApp,
            'tel' => $req->tel,
        ]);
        return response()->json([
            'status' => true,
            'Massage' => 'Contact Us Created Successfully'
        ], 200);
    }
    public function update(Request $req, $id)
    {
        $validatecontactUs = Validator::make(
            $req->all(),
            [
                'email' => 'required',
                'phone' => 'required',
                'whatsApp' => 'required',
                'tel' => 'required',
            ]
        );
        if ($validatecontactUs->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatecontactUs->errors()
            ], 401);
        }
        $contactUs = ContactUs::findOrFail($id);
        $contactUs->update([
            'email' => $req->email,
            'phone' => $req->phone,
            'whatsApp' => $req->whatsApp,
            'tel' => $req->tel,
        ]);
        return response()->json([
            'status' => true,
            'Massage' => 'Contact Us Updated Successfully'
        ], 200);
    }
    public function destroy($id)
    {
        $contactUs = ContactUs::findOrFail($id);
        if (isset($contactUs)) {
            $contactUs->delete();
            return response()->json([
                'status' => true,
                'massage' => 'Contact Us Deleted Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'massage' => 'Contact Us Deleted Faild'
            ], 400);
        }
    }
}
