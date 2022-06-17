<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user() == null) {
            return response()->json('User not authenticated', 200);
        }

        $user = User::whereId(auth()->user()->id)->first();
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (auth()->user() == null) {
            return response()->json('User not authenticated', 200);
        }

        if (isset($request->new_password)) {
            $user = User::where('id', '=', auth()->user()->id)->first();
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
            return response()->json(['status' => 'Success change password'], 200);
        } else {
            $data = $request->all();
            $user->update($data);
        }

        $user = User::whereId($user->id)->first();
        return new UserResource($user);
    }
}
