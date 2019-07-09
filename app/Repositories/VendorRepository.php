<?php
namespace App\Repositories;
use App\Orders as Orders;
use Illuminate\Support\Facades\DB;

class VendorRepository {

    public function getPendingOrders(int $vendor, int $status){
        return DB::table('orders')
                 ->leftJoin('order_products', 'orders.id', '=', 'order_products.orders_id')
                 ->select('orders.*')
                 ->where('order_products.vendor', $vendor)
                 ->where('order_products.status', $status)
                 ->groupBy('orders.id')
                 ->get();
    }

    public function getPendingOrderProducts(int $vendor, int $status){
        return DB::table('orders')
                 ->leftJoin('order_products', 'orders.id', '=', 'order_products.orders_id')
                 ->leftJoin('products', 'order_products.products_id', '=', 'products.id')
                 ->leftJoin('creatives', 'products.creatives_id', '=', 'creatives.id')
                 ->select('order_products.*', 'creatives.url')
                 ->where('order_products.vendor', $vendor)
                 ->where('order_products.status', $status)
                 ->get();
    }

}


?>
