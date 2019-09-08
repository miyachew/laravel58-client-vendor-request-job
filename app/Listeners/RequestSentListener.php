<?php

namespace App\Listeners;

use App\Events\RequestSentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendNewRequestToVendorJob;

class RequestSentListener
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
    public function handle(RequestSentEvent $event)
    {
        SendNewRequestToVendorJob::dispatch($event->clientRequest)->delay(now()->addSeconds(5));
    }
}
