<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function logout(User $user){

        $user->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->only([
            'password',
            'email',
        ]);

        $validation = Validator::make($credentials,[
            'password'  => 'required|max:20|min:6',
            'email'     => 'required|max:250|email|exists:users,email',
        ]);

        if (!$validation->fails()) {

            if (Auth::attempt($credentials)) {

                $user       = $request->user();
                // php artisan passport:install
                $tokenResult    = $user->createToken(config('app.name').' Personal Access Client');
                $token          = $tokenResult->token;

                $tokenUser = [
                    'access_token'  => $tokenResult->accessToken,
                    'token_type'    => 'Bearer',
                ];

                $message    = ['message' => [__('Welcome')]];
                $status     = 'success';
                $data       = $tokenUser;

            } else {
                $message    = ['message' => [__('Invalid credentials')]];
                $status     = 'warning';
                $data       = false;
            }
        }else{
            $message    = $validation->messages();
            $status     = 'warning';
            $data       = false;
        }

        return response([
            'data'      => $data,
            'status'    => $status,
            'message'   => $message
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
