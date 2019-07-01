<?php

use Illuminate\Database\Seeder;

class CreativesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('creatives')->insert([
            'artist_id'     => 2,
            'name'          => 'Godzilla',
            'url'           => 'https://i.imgur.com/6TSZDbz.jpg',
            'description'   => 'Godzilla Roaring'
        ]);
    }
}
