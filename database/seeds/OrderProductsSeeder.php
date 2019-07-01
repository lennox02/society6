<?php

use Illuminate\Database\Seeder;

class OrderProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('order_products')->insert([
            'orders_id'     => 1,
            'products_id'   => 1,
            'qty'           => 1,
            'vendor'        => 1,
            'status'        => 8
        ]);
    }
}
