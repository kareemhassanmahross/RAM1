<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class resetePasswordController extends Controller
{
    public function resetPassword(Request $req)
    {
        try {
            $validateUser = Validator::make(
                $req->all(),
                [
                    'email' => 'required|email|exists:users,email',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user  = User::select('id')->where('email', $req->email)->get();
            if ($user->count() != 1) {
                return response()->json([
                    'status' => false,
                    'Massage' => 'You E-mail not Existing'
                ], 401);
            }
            $email = $req->email;
            Mail::to($email)->send(new SendResetPassword($user));
            return response()->json([
                'status' => true,
                'Massage' => 'We have sent an email'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function newPassword(Request $req)
    {
        $validateUserpassword = Validator::make(
            $req->all(),
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'min:6|required',
                'password_confirmation' => 'required|same:password|min:6'
            ]
        );

        if ($validateUserpassword->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUserpassword->errors()
            ], 401);
        }

        $user = User::firstWhere('email', $req->email);

        $user->update([
            'password' => Hash::make($req->password),
        ]);

        return response()->json([
            'status' => true,
            'Massage' => 'Your password updated successfully',
        ]);
    }
}
