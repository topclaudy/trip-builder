FlightHub Trip Builder V0.1
==========

## Requirements

- PHP 7.3+
- MySQL 5.7+
- Composer

## Installation

- Clone the repository
- In a terminal, `cd` to the project root directory
- Install dependencies: `composer install`
- Create 2 databases, one for the app (Ex: `flighthub`), the other for running tests (Ex: `flighthub_test`)  
- Copy the file `.env.example` to `.env` and set the variables for the app database connection (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
- Copy the file `.env.example` to `.env.testing` and set the variables for the test database connection (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
- Issue the command `php artisan migrate:fresh --seed` to run the migration and populate the database
- Issue the command `php artisan test` to run the feature tests

## Running the application

From the project root directory, issue the command `php artisan serve` to run the PHP's built-in development server. 

By default the HTTP-server will listen to port 8000. However if that port is already in use, you might want to specify what port to use. Just add the --port argument

`php artisan serve --port=8080`

You can now visit the URL `http://127.0.0.1:8000/api/trip` to view the JSON list of trips

## Features

- An API endpoint to create/build trips.
- Simple JSON API to list airlines, airports, flights, and trips (We assume all trips belong to the same user, user 1).
- Trip's type is inferred from the associated flights.
- Support for one-way, round-trip, open-jaw, multi-city trips.
- Support for flights departing and/or arriving in the vicinity of requested locations.
- Ability to sort trip listings in descendant order of departure date (default is ascendant)
- Pagination of trip listings.

## API Documentation

The API documentation can be found here: 


 

