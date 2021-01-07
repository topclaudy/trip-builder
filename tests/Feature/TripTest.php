<?php

namespace Tests\Feature;

use App\Enums\TripType;
use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TripTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private $tripService;

    public function setUp(): void
    {

        parent::setUp();

        $this->tripService = resolve(TripService::class);
    }

    /**
     * Check one-way payload.
     *
     * @test
     * @return void
     */
    public function it_checks_one_way_trip_payload()
    {
        $payload = [
            'flights' => [
                [
                    'departure_date'     => '2021-03-01',
                    'departure_location' => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ]
                ]
            ],
        ];

        $payload = $this->tripService->extractFlightsDefinitionFromPayload($payload);
        $this->assertEquals(TripType::OneWay, $this->tripService->getTypeFromFlights($payload['flights']));
    }

    /**
     * Check round-trip payload.
     *
     * @test
     * @return void
     */
    public function it_checks_round_trip_payload()
    {
        $payload = [
            'flights' => [
                [
                    'departure_date'     => '2021-03-01',
                    'departure_location' => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-05',
                    'departure_location' => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                ],
            ]
        ];

        $payload = $this->tripService->extractFlightsDefinitionFromPayload($payload);
        $this->assertEquals(TripType::RoundTrip, $this->tripService->getTypeFromFlights($payload['flights']));
    }

    /**
     * Check open-jaw trip payload.
     *
     * @test
     * @return void
     */
    public function it_checks_open_jaw_trip_payload()
    {
        $payload = [
            'flights' => [
                [
                    'departure_date'     => '2021-03-01',
                    'departure_location' => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-07',
                    'departure_location' => [
                        'latitude'  => 51.131470,
                        'longitude' => -114.010559,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                ]
            ]
        ];

        $payload = $this->tripService->extractFlightsDefinitionFromPayload($payload);
        $this->assertEquals(TripType::OpenJaw, $this->tripService->getTypeFromFlights($payload['flights']));
    }

    /**
     * Check multi-city payload.
     *
     * @test
     * @return void
     */
    public function it_checks_multi_city_trip_payload()
    {
        $payload = [
            'flights' => [
                [
                    'departure_date'     => '2021-03-01',
                    'departure_location' => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-02',
                    'departure_location' => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.320165,
                        'longitude' => -75.668164,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-07',
                    'departure_location' => [
                        'latitude'  => 45.320165,
                        'longitude' => -75.668164,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 51.131470,
                        'longitude' => -114.010559,
                    ],
                ]
            ]
        ];

        $payload = $this->tripService->extractFlightsDefinitionFromPayload($payload);
        $this->assertEquals(TripType::MultiCity, $this->tripService->getTypeFromFlights($payload['flights']));
    }

    /**
     * Ensure empty payload is unsupported type.
     *
     * @test
     * @return void
     */
    public function it_ensures_empty_payload_are_unsupported_type()
    {
        $payload = [
            'flights' => []
        ];

        $payload = $this->tripService->extractFlightsDefinitionFromPayload($payload);
        $this->assertEquals(TripType::Unsupported, $this->tripService->getTypeFromFlights($payload['flights']));
    }

    /**
     * It ensures a trip is created successfully.
     *
     * @test
     * @return void
     */
    public function it_ensures_a_trip_is_created_successfully()
    {
        $tripsCount = DB::table('trips')->count();
        $tripFlightsCount = DB::table('flight_trip')->count();

        $payload = [
            'flights' => [
                [
                    'departure_date'     => '2021-03-01',
                    'departure_location' => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-02',
                    'departure_location' => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.320165,
                        'longitude' => -75.668164,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-07',
                    'departure_location' => [
                        'latitude'  => 45.320165,
                        'longitude' => -75.668164,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 51.131470,
                        'longitude' => -114.010559,
                    ],
                ],
            ],
        ];

        $response = $this->post('/api/trip/store', $payload);

        $this->assertDatabaseCount('trips', $tripsCount + 1);
        $this->assertDatabaseCount('flight_trip', $tripFlightsCount + count($payload['flights']));
        $response->assertStatus(201);
    }

    /**
     * It ensures a trip is created successfully.
     *
     * @test
     * @return void
     */
    public function it_ensures_a_trip_can_be_created_successfully_using_city_name_as_location()
    {
        $tripsCount = DB::table('trips')->count();
        $tripFlightsCount = DB::table('flight_trip')->count();

        $payload = [
            'flights' => [
                [
                    'departure_date'     => '2021-04-01',
                    'departure_location' => "Montreal",
                    'arrival_location'   => "Vancouver",
                ],
                [
                    'departure_date'     => '2021-04-02',
                    'departure_location' => "Vancouver",
                    'arrival_location'   => "Montreal",
                ]
            ],
        ];

        $response = $this->post('/api/trip/store', $payload);

        $this->assertDatabaseCount('trips', $tripsCount + 1);
        $this->assertDatabaseCount('flight_trip', $tripFlightsCount + count($payload['flights']));
        $response->assertStatus(201);
    }

    /**
     * It ensures trip with invalid flights can't be created.
     *
     * @test
     * @return void
     */
    public function it_ensures_trip_with_at_least_one_unfound_airport_location_cant_be_created()
    {
        $response = $this->post('/api/trip/store', [
            'flights' => [
                [
                    'departure_date'     => '2021-03-01',
                    'departure_location' => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                ]
                ,
                [
                    'departure_date'     => '2021-03-02',
                    'departure_location' => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.320165,
                        'longitude' => -75.668164,
                    ],
                ]
                ,
                [
                    'departure_date'     => '2021-03-07',
                    'departure_location' => [ //We won't find an airport for this location
                        'latitude'  => 1.194698,
                        'longitude' => -1.179192,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                ],
            ],
        ]);

        $response->assertSee('No departure airport found around the location');
        $response->assertStatus(422);
    }

    /**
     * It ensures trip with invalid dates can't be created.
     *
     * @test
     * @return void
     */
    public function it_ensures_trip_with_invalid_dates_cant_be_created()
    {
        $response = $this->post('/api/trip/store', [
            'flights' => [
                [
                    'departure_date'     => '2019-03-01', //Date in the past
                    'departure_location' => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-02',
                    'departure_location' => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.320165,
                        'longitude' => -75.668164,
                    ],
                ],
                [
                    'departure_date'     => '2025-03-07',
                    'departure_location' => [
                        'latitude'  => 45.320165,
                        'longitude' => -75.668164,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 51.131470,
                        'longitude' => -114.010559,
                    ],
                ],
            ],
        ]);

        $response->assertSee('The trip departure date is not valid');
        $response->assertSee('The flight date is not valid');
        $response->assertStatus(422);
    }

    /**
     * It ensures trip with unavailable can't be created.
     *
     * @test
     * @return void
     */
    public function it_ensures_trip_with_unavailable_flight_cant_be_created()
    {
        $response = $this->post('/api/trip/store', [
            'flights' => [
                [
                    'departure_date'     => '2021-08-06',
                    'departure_location' => [ //matches YUL airport...
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [ //matches YYC airport...
                        'latitude'  => 51.131470,
                        'longitude' => -114.010559,
                    ],
                ] //... but there is not flight from YUL to YYC
            ],
        ]);

        $response->assertSee('No flight found from YUL to YYC');
        $response->assertStatus(422);
    }

    /**
     * It ensures unsupported trip type can't be created.
     *
     * @test
     * @return void
     */
    public function it_ensures_unsupported_trip_type_cant_be_created()
    {
        $response = $this->post('/api/trip/store', [
            'flights' => [
                [
                    'departure_date'     => '2021-03-01',
                    'departure_location' => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-02',
                    'departure_location' => [
                        'latitude'  => 49.194698,
                        'longitude' => -123.179192,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.320165,
                        'longitude' => -75.668164,
                    ],
                ],
                [
                    'departure_date'     => '2021-03-07',
                    'departure_location' => [
                        'latitude'  => 51.131470,
                        'longitude' => -114.010559,
                    ],
                    'arrival_location'   => [
                        'latitude'  => 45.457714,
                        'longitude' => -73.749908,
                    ],
                ],
            ],
        ]);

        $response->assertSee('This type of trip is not currently supported');
        $response->assertStatus(422);
    }
}
