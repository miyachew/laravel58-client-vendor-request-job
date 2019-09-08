<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\SendMailService;
use App\ClientRequest;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendRequestPaymentUpdateToVendorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientRequest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ClientRequest $clientRequest)
    {
        $this->clientRequest = $clientRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SendMailService $sendMailService)
    {
        if(!$this->clientRequest || !$this->clientRequest->client_name || !$this->clientRequest->vendor_email){
            return ;
        }
        
        $subject = 'Received payment for client request';

        $paymentDate = Carbon::parse($this->clientRequest->payment_date, 'UTC')->format('Y-m-d H:i');
        $content = sprintf("You've receive a new payement from %s. Payment Date %s. payment method: %s, payment transaction reference: %s",
                    $this->clientRequest->client_name,
                    $paymentDate,
                    $this->clientRequest->payment_method,
                    $this->clientRequest->transaction_reference
                ); 
        $sendMailService->sendRaw($this->clientRequest->vendor_email, $subject, $content);
    }
}
