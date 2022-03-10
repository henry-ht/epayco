<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class PaymentController extends Controller
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
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->only([
            'amount',
            'identification',
        ]);

        $validation = Validator::make($credentials,[
            'amount'            => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'identification'    => 'required|numeric|exists:users,identification',
        ]);


        if (!$validation->fails()) {
            $user_id = User::where('identification', $credentials['identification'])
                            ->with(['balance'])
                            ->first();

            if(isset($user_id)){

                if($user_id->balance->amount >= $credentials['amount']){

                    $user_id->balance->amount = $user_id->balance->amount - $credentials['amount'];
                    $user_id->balance->save();
                    Payment::create([
                        'user_id'   => $user_id->id,
                        'paid_out'  => $credentials['amount'],
                        'status'    => 'complete'
                    ]);

                    $message    = ['message' => [__('Payment made'), ]];
                    $status     = 'success';
                    $data       = true;
                }else{
                    $message    = ['message' => [__('Insufficient amount')]];
                    $status     = 'warning';
                    $data       = false;
                }


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
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
