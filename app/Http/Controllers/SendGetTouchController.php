<?php

namespace App\Http\Controllers;

use App\Mail\gettouch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class SendGetTouchController extends Controller
{
    public function getTouch(Request $req)
    {

        $validateData = Validator::make(
            $req->all(),
            [
                'name'         => 'required',
                'email'        => 'required',
                'massage'    => 'required',
                'phone'    => 'required',
            ]
        );
        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateData->errors()
            ], 401);
        }
        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'massage' => $req->massage,
            'phone' => $req->phone,
        ];
        Mail::to('kareemdiap508@gmail.com')->send(new gettouch($data));
        return response()->json([
            'status' => true,
            'massage' => 'Successfully'
        ], 200);
    }
}