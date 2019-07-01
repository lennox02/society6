<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('products')->insert([
            'creatives_id'  => 1,
            'product_type'  => 1,
            'name'          => 'Small Godzilla Roaring T-Shirt'
        ]);

        DB::table('products')->insert([
            'creatives_id'  => 1,
            'product_type'  => 6,
            'name'          => 'Godzilla Roaring Print'
        ]);
    }
}
