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

class SendNewRequestToVendorJob implements ShouldQueue
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
        
        $subject = 'New request received';
        $content = sprintf("You've receive a new request from %s",$this->clientRequest->client_name); 
        $sendMailService->sendRaw($this->clientRequest->vendor_email, $subject, $content);

        $this->clientRequest->status = ClientRequest::STATUS_REQUEST_SENT;
        $this->clientRequest->save();
    }
}
