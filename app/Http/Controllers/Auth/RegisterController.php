<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Mail\RegisterEmail;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{

    private $apiToken;
    public function __construct()
    {
        $this->apiToken = uniqid(base64_encode(Str::random(40)));
    }

    public function register(UserRequest $request){

        // Validate user details
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_no' => 'required | string | max:14 | min:10 | unique:users',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'phone_no' => $validatedData['phone_no'],
            'email' => $validatedData['email'],
            'tel_no' => $request->tel_no,
            'password' => Hash::make($validatedData['password']),
            'remember_token' => Str::random(10)
        ]);
        
        $token = $user->createToken($this->apiToken)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'name' => $user->name,
        ]);
    }

    public function registerVendor(Request $request){
        // Validate user details
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_no' => 'required | string | max:14 | min:10 | unique:users',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = DB::transaction(function() use($validatedData, $request){
            $user = User::create([
                'name' => $validatedData['name'],
                'phone_no' => $validatedData['phone_no'],
                'email' => $validatedData['email'],
                'tel_no' => $request->tel_no,
                'password' => Hash::make($validatedData['password']),
                'remember_token' => Str::random(10)
            ]);

            $vendor = Vendor::create([
                'user_id' => $user->id,
            ]);

            return $user;
        });

        return response()->json([
            'access_token' => $this->apiToken,
            'token_type' => 'Bearer',
            'name' => $user->name,
            'user_id' =>$user->id
        ]);
    }

    public function logout(Request $request) {        
        
        Auth::user()->tokens->each(function($token, $key){
            $token->delete();
        });
        Auth::guard('web')->logout();
        return response()->json('successfully logged out', 204);
    }
}
