<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'first_name'    => 'James',
            'last_name'     => 'Webb',
            'email'         => 'james@webb.com',
            'password'      => 'blah',
            'address'       => '123 Happy Street',
            'address_2'     => 'Apt 101',
            'city'          => 'Santa Monica',
            'state'         => 'CA',
            'zip'           => '90001',
            'country'       => 'US'
        ]);

        DB::table('users')->insert([
            'first_name'    => 'Artist',
            'last_name'     => 'McCool',
            'email'         => 'artist@art.com',
            'password'      => 'blah',
            'address'       => '456 Cool Street',
            'address_2'     => 'Unit 1',
            'city'          => 'Santa Monica',
            'state'         => 'CA',
            'zip'           => '90001',
            'country'       => 'US'
        ]);
    }
}
