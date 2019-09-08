<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_name');
            $table->string('vendor_email');
            $table->string('status');
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_requests');
    }
}
