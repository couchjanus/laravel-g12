<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRoutes()
    {
        $appURL = env('APP_URL');

        $urls = [
            '/',
            '/hello',
            '/home' 
        ];

        echo  PHP_EOL;

        foreach ($urls as $url) {
            $response = $this->get($url);
            if((int)$response->status() !== 200){
                echo  $appURL . $url . ' (FAILED) did not return a 200.';
                $this->assertFalse(false);
            } else {
                echo $appURL . $url . ' (success) return a 200!';
                $this->assertTrue(true);
            }
            echo  PHP_EOL;
        }

    }

    public function testFoobarGet()
    {
        $response = $this->get('/foobar');
        $response->assertStatus(200);
    }

    public function testFoobarPost()
    {
        $response = $this->post('/foobar');
        $response->assertStatus(200);
    }

    public function testFoomarGet()   {
        $response = $this->get('/foomar');
        $response->assertStatus(200);
    }
    public function testFoomarPut()   {
        $response = $this->put('/foomar');
        $response->assertStatus(200);
    }
    public function testFoomarPatch()   {
        $response = $this->patch('/foomar');
        $response->assertStatus(200);
    }

    public function testBarName()
    {
        $response = $this->get(route('bar'));
        $response->assertStatus(200);
    }

    public function testBarabName()   {
        $response = $this->get(url('barab'));
        $response->assertStatus(200);
    }

}
