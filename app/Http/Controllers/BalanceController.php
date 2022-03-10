<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $credentials = $request->only([
            'phone',
            'identification',
        ]);

        $validation = Validator::make($credentials,[
            'phone'             => 'required|max:30|min:7|exists:users,phone',
            'identification'    => 'required|numeric|exists:users,identification',
        ]);

        if (!$validation->fails()) {

            $user_id = User::where('identification', $credentials['identification'])
                            ->where('phone', $credentials['phone'])
                            ->with(['balance'])
                            ->first();

            if(isset($user_id)){
                // $balance = Balance::where('user_id', $user_id->id)->first();

                $message    = ['message' => [__('Balance'), ]];
                $status     = 'success';
                $data       = $user_id->balance;

            }else{
                $message    = ['message' => [__('User does not exist')]];
                $status     = 'warning';
                $data       = false;
            }

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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->only([
            'description',
            'amount',
            'phone',
            'identification',
        ]);

        $validation = Validator::make($credentials,[
            'amount'            => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'phone'             => 'required|max:30|min:7|exists:users,phone',
            'identification'    => 'required|numeric|exists:users,identification',
            'description'       => 'sometimes|required|max:150|min:3|string',
        ]);


        if (!$validation->fails()) {

            $user_id = User::where('identification', $credentials['identification'])
                            ->where('phone', $credentials['phone'])
                            ->first();

            unset($credentials['identification']);
            unset($credentials['phone']);

            if(isset($user_id)){

                $balance = Balance::where('user_id', $user_id->id)->first();

                $balance = $balance ? $balance->amount:0;
                $credentials['amount'] = $balance+$credentials['amount'];

                Balance::updateOrCreate([
                    'user_id' => $user_id->id
                ], $credentials);

                $message    = ['message' => [__('Successful')]];
                $status     = 'success';
                $data       = true;

            }else{
                $message    = ['message' => [__('User does not exist')]];
                $status     = 'warning';
                $data       = false;
            }

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
     * @param  \App\Models\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function show(Balance $balance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Balance $balance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Balance $balance)
    {
        //
    }
}
