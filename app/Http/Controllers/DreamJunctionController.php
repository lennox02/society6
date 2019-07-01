<?php

namespace App\Http\Controllers;

use App\OrderProducts as OrderProducts;
use App\Orders as Orders;
use App\Users as Users;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class DreamJunctionController extends Controller
{

    /*
        DREAM JUNCTION ORDER FORMAT EXAMPLE
        <orders>
            <order>
                <order_number>12345</order_number>
                <customer_data>
                    <first_name>John</first_name>
                    <last_name>Doe</last_name>
                    <address1>123 Main Street</address1>
                    <address2 />
                    <city>Santa Monica</city>
                    <state>CA</state>
                    <zip>90014</zip>
                    <country>US</country>
                </customer_data>
                <items>
                    <item>
                        <order_line_item_id>45678</order_line_item_id>
                        <product_id>12</product_id>
                        <quantity>1</quantity>
                        <image_url>https://bucket.s3.amazonsaws.com/images/image.jpg</image_url>
                    </item>
                </items>
            </order>
        </orders>
    */

    const VENDOR_ID = 1;

    public function getPendingOrders(){
        //custom query getting all pending order ids
        $pendingOrders = DB::table('orders')
                           ->leftJoin('order_products', 'orders.id', '=', 'order_products.orders_id')
                           ->select('orders.*')
                           ->where('order_products.vendor', self::VENDOR_ID)
                           ->where('order_products.status', Orders::STATUS_PENDING)
                           ->groupBy('orders.id')
                           ->get();

       $pendingOrderProducts = DB::table('orders')
                                 ->leftJoin('order_products', 'orders.id', '=', 'order_products.orders_id')
                                 ->leftJoin('products', 'order_products.products_id', '=', 'products.id')
                                 ->leftJoin('creatives', 'products.creatives_id', '=', 'creatives.id')
                                 ->select('order_products.*', 'creatives.url')
                                 ->where('order_products.vendor', self::VENDOR_ID)
                                 ->where('order_products.status', Orders::STATUS_PENDING)
                                 ->get();

        echo $this->formatOrders($pendingOrders, $pendingOrderProducts);
    }

    public function formatOrders(Collection $orders, Collection $orderProducts){
        //group order products by order id
        $groupedOrderProducts = [];
        foreach($orders as $order){
            foreach($orderProducts as $op){
                if($op->orders_id === $order->id){
                    $groupedOrderProducts[$order->id][] = $op;
                }
            }
        }

        $formatted = "<orders>";
        foreach($orders as $order){
            $formatted .= $this->formatOrder($order, $groupedOrderProducts[$order->id]);
        }
        $formatted .= "</orders>";

        return $formatted;

    }

    public function formatOrder(stdClass $order, array $orderProducts){

        $orderItems = $this->formatOrderItems($orderProducts);

        //get order user data
        $user = Users::find($order->user_id);

        $formatted =  "<order>";
        $formatted .= "<order_number>"      . $order->id        . "</order_number>";
        $formatted .= "<customer_data>";
        $formatted .= "<first_name>"        . $user->first_name . "</first_name>";
        $formatted .= "<address1>"          . $user->address    . "</address1>";
        $formatted .= "<address2>"          . $user->address_2  . "</address2>";
        $formatted .= "<city>"              . $user->city       . "</city>";
        $formatted .= "<state>"             . $user->state      . "</state>";
        $formatted .= "<zip>"               . $user->zip        . "</zip>";
        $formatted .= "<country>"           . $user->country    . "</country>";
        $formatted .= "</customer_data>";
        $formatted .= "<items>";
        $formatted .= $orderItems;
        $formatted .= "</items>";
        $formatted .= "</order>";

        return $formatted;
    }

    public function formatOrderItems(array $orderProducts){

        $formatted = "";

        foreach ($orderProducts as $orderProduct) {
            $formatted .= "<item>";
            $formatted .= "<order_line_item_id>"    . $orderProduct->id          . "</order_line_item_id>";
            $formatted .= "<product_id>"            . $orderProduct->products_id . "</product_id>";
            $formatted .= "<quantity>"              . $orderProduct->qty         . "</quantity>";
            $formatted .= "<image_url>"             . $orderProduct->url         . "</image_url>";
            $formatted .= "</item>";
        }

        return $formatted;

    }

}
