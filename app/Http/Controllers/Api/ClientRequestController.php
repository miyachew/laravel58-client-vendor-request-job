<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ClientRequest;
use App\Events\RequestSentEvent;
use App\Events\RequestUpdatePaymentEvent;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClientRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string',
            'vendor_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()], 422);
        }

        try{
            $clientRequest = new ClientRequest();
            $clientRequest->client_name = $request->get('client_name') ?? '';
            $clientRequest->vendor_email = $request->get('vendor_email') ?? '';
            $clientRequest->status = ClientRequest::STATUS_NEW;
            $clientRequest->save();

            event(new RequestSentEvent($clientRequest));
            return response()->json($clientRequest);
        } catch (\Exception $exception){
            Log::critical($exception->getMessage());
            throw new \RuntimeException(__('error.transaction_error'), 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updatePayment(int $id, Request $request)
    {
        $clientRequest = ClientRequest::where('id',$id)->first();
        if(!$clientRequest){
            return response()->json(['errors'=>'Client request not found' ], 404);
        }

        if($clientRequest->status === ClientRequest::STATUS_PAYMENT_DONE){
            return response()->json(['errors'=>'Already updated payment info.' ], 404);
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'transaction_reference' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()], 422);
        }

        try{
            $clientRequest->status = ClientRequest::STATUS_PAYMENT_DONE;
            $clientRequest->payment_date = Carbon::now();
            $clientRequest->payment_method = $request->get('payment_method') ?? '';
            $clientRequest->transaction_reference = $request->get('transaction_reference') ?? '';
            $clientRequest->save();
            
            event(new RequestUpdatePaymentEvent($clientRequest));
            return response()->json(['msg'=>'Update successfully.']);
        } catch (\Exception $exception){
            Log::critical($exception->getMessage());
            throw new \RuntimeException(__('error.transaction_error'), 500);
        }
    }
}
