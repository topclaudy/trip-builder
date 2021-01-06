<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlightTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function it_ensures_flights_are_available()
    {
        $response = $this->get('/api/flight');

        $response->assertStatus(200);
        $response->assertJsonFragment(['departure_airport' => 'YUL']);
    }
}
