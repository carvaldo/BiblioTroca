<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct() {

    }

    public function authenticate(Request $request)
    {
        dd($request->post());
    }
}
