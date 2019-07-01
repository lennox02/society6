<?php

namespace App\Http\Controllers;

use App\OrderProducts as OrderProducts;
use App\Orders as Orders;
use App\Users as Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DreamJunctionController extends Controller
{

    /*
        NOTE to reviewers

        The following controller follows a pattern of nested selects.  This can
        be disadvantageus due to having a higher number of queries, however,
        given that order data tends to require a relatively low number of total
        queries I think it's a good fit, making the code easier to decipher,
        along with keeping data restricted to it's respective model.

        However if I wanted to do a single joined query and then parse that into
        the correct format my raw SQL would look like this:

        SELECT *
        FROM orders o
        LEFT JOIN users u
        ON o.user_id = u.id
        LEFT JOIN order_products op
        ON o.id = op.orders_id
        LEFT JOIN
        WHERE o.status = Orders::STATUS_PENDING
        AND op.vendor = self::VENDOR_ID

        NOTE #2
        On Further reflection my solution's performance will most liekly degrade
        at Order::whereIn() in getPendingOrders() with a few thousand ids.  My
        new preference would be to refactor with the above solution

    */

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
        $pendingOrderIds = DB::table('orders')
                             ->leftJoin('order_products', 'orders.id', '=', 'order_items.id')
                             ->select('orders.id')
                             ->where('orders_products.vendor', self::VENDOR_ID)
                             ->where('orders_products.status', Orders::STATUS_PENDING)
                             ->get();

        $orders = OrderProducts::whereIn('id', $pendingOrderIds);
    }

    public function formatOrders(Orders $orders){

        $formatted = "<orders>";
        foreach($orders as $order){
            $formatted .= $this->formatOrder($order);
        }
        $formatted .= "</orders>";

        return $formatted;

    }

    public function formatOrder(Orders $order){

        //get order products data
        $orderProducts = OrderProducts::where('order_id', $order->id)
                                      ->where('vendor', self::VENDOR_ID);
        $orderItems = $this->formatOrderItems($orderProducts);

        //get order user data
        $user = User::find($order->user_id);

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

    public function formatOrderItems(OrderProducts $orderProducts){

        $formatted = "";

        foreach ($orderProducts as $orderProduct) {
            $formatted .= "<item>";
            $formatted .= "<order_line_item_id>"    . $orderProduct->id         . "</order_line_item_id>";
            $formatted .= "<product_id>"            . $orderProduct->product_id . "</product_id>";
            $formatted .= "<quantity>"              . $orderProduct->qty        . "</quantity>";
            $formatted .= "<image_url>"             . $orderProduct->url        . "</image_url>";
            $formatted .= "</item>";
        }

        return $formatted;

    }

}
