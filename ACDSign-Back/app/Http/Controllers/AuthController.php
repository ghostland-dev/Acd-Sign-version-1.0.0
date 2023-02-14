<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    //Permet la création d'un utilisateur
    public function register(Request $request)
    {
        /* if (! $request->hasValidSignature()) {
            abort(401);
        } */

        //Permet de créer une validation des données
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required','email', 'unique:users,email'],
            'password' => ['required','string', 'min:8', 'max:30', 'confirmed'],
            'first_name' => ['required', 'string', 'min:2', 'max:40'],
            'last_name' => ['required', 'string', 'min:2', 'max:40'],
        ]);

        //Créer un user
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            //Hash et Salt le mot de passe
            'password' => Hash::make($request['password']),
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name']
        ]);

        return response($user, 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $customPayload = [
            'email' => $request->email,
        ];

        $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string', 'min:8'],
        ]);

        try {
            if (! $token = JWTAuth::customClaims(['user' => $customPayload])->attempt($credentials)) {
            //if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
}
