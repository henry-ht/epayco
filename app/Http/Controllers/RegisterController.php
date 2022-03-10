<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->only([
            'name',
            'email',
            'phone',
            'identification',
            'document_id',
        ]);

        $validation = Validator::make($credentials,[
            'name'              => 'required|max:150|min:10|string',
            'phone'             => 'sometimes|required|max:30|min:7|unique:users,phone',
            'email'             => 'required|max:250|email|unique:users,email',
            'identification'    => 'required|numeric',
            'document_id'       => 'required|integer|exists:documents,id',
        ]);

        if (!$validation->fails()) {
            $credentials['password']    = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
            $credentials['email_verified_token'] = Str::random(60);
            $credentials['email_verified_at'] = Carbon::now();

            $newUser   = User::create($credentials);

            $message    = ['message' => [__('Successful registration')]];
            $status     = 'success';
            $data       = true;


        }else{
            $message    = $validation->messages();
            $status     = 'warning';
            $data       = false;
        }

        return response([
            'data'          => $data,
            'status'        => $status,
            'message'       => $message
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
