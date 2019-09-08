<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientRequestControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostRequestWithoutData()
    {
        $response = $this->post('/api/requests');
        $response->assertStatus(422);
    }

    public function testPostRequestSuccessful(){
      $data = [
        'client_name'=>'test client name',
        'vendor_email'=> 'vendor@email.com'
      ];
      $path = action(sprintf('%s@postRequest', 'Api\ClientRequestController'));

      $response = $this->call('POST', $path, $data);
      $response->assertStatus(200);

      $this->assertDatabaseHas('client_requests',$data);
    }
}
