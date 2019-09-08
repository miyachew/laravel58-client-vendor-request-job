<?php

namespace App\Services;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Log;

class SendMailService
{
    /** @var mailer */
    private $mailer;

    public function __construct(
        Mailer $mailer
    ) {
        $this->mailer = $mailer;
    }

    /**
     * @param $toAddress
     * @param $subject
     * @param $data
     * @param $content
     */
    public function sendRaw($toAddress, $subject, $content): void
    {
        try{
            $this->mailer->raw($content, function ($message) use ($subject, $toAddress) {
                $message->to($toAddress)->subject($subject);
            });
        } catch (\Exception $exception){
            Log::critical($exception->getMessage());
        }
    }
}
