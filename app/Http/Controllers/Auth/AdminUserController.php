<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    // public function __construct()
    // {
    //     if (Auth::guard('admins')->check()) {
    //         $this->middleware('auth:sanctum')->except('createUser', 'loginUser');
    //     } else {
    //         // dd('Your User');
    //         $this->middleware('auth:user');
    //     }
    // }
    public function index()
    {
        $Alluser = Admin::select('id', 'name', 'email', 'address1', 'address2', 'phone')->get();
        if ($Alluser->count() == 0) {
            return response()->json([
                'status' => true,
                'massage' => 'There are no registered users yet'
            ], 200);
        }
        return response($Alluser);
    }
    public function createAdmin(Request $request)
    {
        try {
            //Validated
            $validateAdmin = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:admins,email',
                    'password' => 'required',
                    'address1' => 'required',
                    'address2' => 'required',
                    'phone' => 'required'
                ]
            );

            if ($validateAdmin->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateAdmin->errors()
                ], 401);
            }
            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address1' => $request->address1,
                'address2' => $request->address2,
                'phone' => $request->phone,
            ]);

            return response()->json([
                'userAdmin' => $user,
                'status' => true,
                'message' => 'Admin Created Successfully',
                'token' => $user->createToken("API TOKEN Admin (" . $request->email . " )")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUserAdmin(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email|exists:admins,email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            // dd(!Auth::attempt($request->only(['email', 'password'])));
            if (!Auth::guard('admins')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = Admin::select('id', 'name', 'email', 'address1', 'address2', 'password')->where('email', $request->email)->first();
            // dd($user->password === $request->password);
            // dd(Hash::check($request->password, $user->password));
            if ($user && Hash::check($request->password, $user->password)) {
                return response()->json([
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'address1' => $user->address1,
                        'address2' => $user->address2,
                    ],
                    'status' => true,
                    'message' => 'Admin Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }
            // return response()->json([
            //     'user' => $user,
            //     'status' => true,
            //     'message' => 'Admin Logged In Successfully',
            //     'token' => $user->createToken("API TOKEN" . $request->name)->plainTextToken
            // ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function logoutUser(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'logged out successfully',
        ], 200);
    }
    public function ShowUserAdmin($id)
    {
        try {
            $user = Admin::findOrFail($id);
            return response()->json([
                'user' => $user,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}