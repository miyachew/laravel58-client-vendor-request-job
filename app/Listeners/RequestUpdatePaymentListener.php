<?php

namespace App\Listeners;

use App\Events\RequestUpdatePaymentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendRequestPaymentUpdateToVendorJob;

class RequestUpdatePaymentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  RequestSentEvent  $event
     * @return void
     */
    public function handle(RequestUpdatePaymentEvent $event)
    {
        SendRequestPaymentUpdateToVendorJob::dispatch($event->clientRequest);
    }
}
