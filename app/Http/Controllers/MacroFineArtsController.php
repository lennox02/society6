<?php

namespace App\Http\Controllers;

use App\OrderProducts as OrderProducts;
use App\Orders as Orders;
use App\Users as Users;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class MacroFineArtsController extends Controller
{

    /*
        NOTE to reviewers

        I've added new lines (\n) in the formatting to make it easier to read
        for you.  However, the data should be digestable without this additional
        formatting.
    */

    /*
        MACRO FINE ARTS ORDERS FORMAT EXAMPLE
        {
            "data": {
                "orders": [
                    {
                        "external_order_id": "12345",
                        "buyer_first_name": "John",
                        "buyer_last_name": "Doe",
                        "buyer_shipping_address_1": "123 Main Street",
                        "buyer_shipping_address_2": "Unit 1",
                        "buyer_shipping_city": "Santa Monica",
                        "buyer_shipping_state": "CA",
                        "buyer_shipping_postal": "90014",
                        "buyer_shipping_country": "US",
                        "print_line_items": [
                            {
                                "external_order_line_item_id": "45678",
                                "product_id": "12",
                                "quantity": "1",
                                "image_url": "https://bucket.s3.amazonsaws.com/images/image.jpg"
                            }
                        ]
                    }
                ]
            }
        }

    */

    const VENDOR_ID = 2;

    public function getPendingOrders(){
        //custom query getting all pending order ids
        $pendingOrders = DB::table('orders')
                           ->leftJoin('order_products', 'orders.id', '=', 'order_products.orders_id')
                           ->select('orders.*')
                           ->where('order_products.vendor', self::VENDOR_ID)
                           ->where('order_products.status', Orders::STATUS_PENDING)
                           ->groupBy('orders.id')
                           ->get();


        if(count($pendingOrders)){
            $pendingOrderProducts = DB::table('orders')
                                     ->leftJoin('order_products', 'orders.id', '=', 'order_products.orders_id')
                                     ->leftJoin('products', 'order_products.products_id', '=', 'products.id')
                                     ->leftJoin('creatives', 'products.creatives_id', '=', 'creatives.id')
                                     ->select('order_products.*', 'creatives.url')
                                     ->where('order_products.vendor', self::VENDOR_ID)
                                     ->where('order_products.status', Orders::STATUS_PENDING)
                                     ->get();

            echo $this->formatOrders($pendingOrders, $pendingOrderProducts);
        } else {
            echo '';
        }
    }

    public function formatOrders(Collection $orders, Collection $orderProducts){

        $oTotal = count($orders);
        $x = 1;

        //group order products by order id
        $groupedOrderProducts = [];
        foreach($orders as $order){
            foreach($orderProducts as $op){
                if($op->orders_id === $order->id){
                    $groupedOrderProducts[$order->id][] = $op;
                }
            }
        }

        $formatted = '"{ data": {' . "\n" . '"orders": [';
        foreach($orders as $order){
            $formatted .= $this->formatOrder($order, $groupedOrderProducts[$order->id]);
            //don't add comma to last item in array - better safe than sorry
            if($x !== $oTotal){
                $formatted .= ',';
            }
            $x++;
        }
        $formatted .= "]\n}\n}";

        return $formatted;

    }

    public function formatOrder(stdClass $order, array $orderProducts){

        $orderItems = $this->formatOrderItems($orderProducts);

        //get order user data
        $user = Users::find($order->user_id);

        $formatted =  ' { ' . "\n";
        $formatted .= '"external_order_id": "'           . $order->id        . '", ' . "\n";
        $formatted .= '"buyer_first_name": "'            . $user->first_name . '", ' . "\n";
        $formatted .= '"buyer_last_name": "'             . $user->last_name  . '", ' . "\n";
        $formatted .= '"buyer_shipping_address_1": "'    . $user->address    . '", ' . "\n";
        $formatted .= '"buyer_shipping_address_2": "'    . $user->address_2  . '", ' . "\n";
        $formatted .= '"buyer_shipping_city": "'         . $user->city       . '", ' . "\n";
        $formatted .= '"buyer_shipping_state": "'        . $user->state      . '", ' . "\n";
        $formatted .= '"buyer_shipping_country": "'      . $user->country    . '", ' . "\n";
        $formatted .= '"print_line_items": [';
        $formatted .= $orderItems;
        $formatted .= ' ]' . "\n";
        $formatted .= ' }' . "\n";

        return $formatted;
    }

    public function formatOrderItems(array $orderProducts){

        $formatted = "";

        $opTotal = count($orderProducts);
        $x = 1;

        foreach ($orderProducts as $orderProduct) {
            $formatted .= ' { ' . "\n";
            $formatted .= '"external_order_line_item_id": "' . $orderProduct->id          . '", ' . "\n";
            $formatted .= '"product_id": "'                  . $orderProduct->products_id . '", ' . "\n";
            $formatted .= '"quantity": "'                    . $orderProduct->qty         . '", ' . "\n";
            $formatted .= '"image_url": "'                   . $orderProduct->url         . '", ' . "\n";
            $formatted .= ' }' . "\n";
            //don't add comma to last item in array - better safe than sorry
            if($x !== $opTotal){
                $formatted .= ',';
            }
            $x++;
        }

        return $formatted;

    }


}
