<?php

use Illuminate\Database\Seeder;

class ProductTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('product_types')->insert([
            'name'          => 't-shirt-s',
            'description'   => 'Small T-Shirt',
            'vendor'        => 1
        ]);

        DB::table('product_types')->insert([
            'name'          => 't-shirt-m',
            'description'   => 'Medium T-Shirt',
            'vendor'        => 1
        ]);

        DB::table('product_types')->insert([
            'name'          => 't-shirt-l',
            'description'   => 'Large T-Shirt',
            'vendor'        => 1
        ]);

        DB::table('product_types')->insert([
            'name'          => 't-shirt-xl',
            'description'   => 'Xtra Large T-Shirt',
            'vendor'        => 1
        ]);

        DB::table('product_types')->insert([
            'name'          => 't-shirt-xxl',
            'description'   => 'Xtra Xtra Large T-Shirt',
            'vendor'        => 1
        ]);

        DB::table('product_types')->insert([
            'name'          => 'print',
            'description'   => 'Fine Art Print',
            'vendor'        => 2
        ]);

    }
}
