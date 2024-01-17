<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createUser(RegisterRequest $request)
    {
        User::create($request->validated());
        return response();
    }
}
