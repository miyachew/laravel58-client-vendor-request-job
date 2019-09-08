<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientRequest extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_REQUEST_SENT = 'request_sent';
    const STATUS_PAYMENT_DONE = 'payment_done';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name',
        'vendor_email'
    ];
}