<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViwesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWelcomeView()
    {
        $response = $this->get('/');
        $response->assertViewIs('welcome');
    }

    // public function testHeyHeyView()
    // {
    //     $response = $this->get('/hey');
    //     $response->assertViewIs('hey');
    // }

    public function testHeyView()  {
        $response = $this->get('/hey');
        $response->assertViewIs('hello');
    }
    
    public function assert_view_has_bazuka()
    {
        $this->get('bazuka')
            ->assertViewHas('title', 'Bazuka Page');
    }




}
