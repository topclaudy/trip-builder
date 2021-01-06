<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'              => 'Trip Guy',
            'email'             => 'trip@flighthub.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('12345'),
        ]);
    }
}
