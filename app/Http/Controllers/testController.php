<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
    public function index()
    {
        $user =  auth('sanctum')->user()->name;
        dd($user);
    }
}
