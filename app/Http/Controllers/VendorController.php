<?php

namespace App\Http\Controllers;

use App\OrderProducts as OrderProducts;
use App\Orders as Orders;
use App\Users as Users;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

abstract class VendorController extends Controller
{

    const VENDOR_ID = 0;

    public function getPendingOrders(){
        //custom query getting all pending order ids
        $pendingOrders = DB::table('orders')
                           ->leftJoin('order_products', 'orders.id', '=', 'order_products.orders_id')
                           ->select('orders.*')
                           ->where('order_products.vendor', static::VENDOR_ID)
                           ->where('order_products.status', Orders::STATUS_PENDING)
                           ->groupBy('orders.id')
                           ->get();


        if(count($pendingOrders)){
            $pendingOrderProducts = DB::table('orders')
                                     ->leftJoin('order_products', 'orders.id', '=', 'order_products.orders_id')
                                     ->leftJoin('products', 'order_products.products_id', '=', 'products.id')
                                     ->leftJoin('creatives', 'products.creatives_id', '=', 'creatives.id')
                                     ->select('order_products.*', 'creatives.url')
                                     ->where('order_products.vendor', static::VENDOR_ID)
                                     ->where('order_products.status', Orders::STATUS_PENDING)
                                     ->get();

            echo $this->formatOrders($pendingOrders, $pendingOrderProducts);
        } else {
            echo '';
        }
    }

    abstract public function formatOrders(Collection $orders, Collection $orderProducts);
    abstract public function formatOrder(stdClass $order, array $orderProducts);
    abstract public function formatOrderItems(array $orderProducts);
}
