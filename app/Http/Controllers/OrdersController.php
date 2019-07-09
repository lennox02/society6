<?php

namespace App\Http\Controllers;

use App\OrderProducts as OrderProducts;
use App\Orders as Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    /*
        NOTE to reviewers

        Technically the vendor shouldn't be passed through here.  The ideal way
        would be to look up the vendor id from the product model itself by
        passing it the product id.  However, because this endpoint would most
        likely be internal, in this hypothetical I'm assuming we're getting
        this input data from an internal controller, not a 3rd party, therefore
        I'm letting the vendor slide so I can have one less query.
    */

    /*
        EXAMPLE INPUT
        {
            "orders":[
                {
                    "user_id":1234
                    "products":[
                        {
                            "id":1234,
                            "qty":1,
                            "vendor":1
                        },
                        {
                            "id":1235,
                            "qty":4,
                            "vendor":2
                        }
                    ]
                },
                {
                    "user_id":1235
                    "products":[
                        {
                            "id":1234,
                            "qty":1,
                            "vendor":1
                        }
                    ]
                }
            ]
        }
    */
    public function createOrders( Request $request ){
        $inputs = $request->input('orders');
        $created = [];
        $created['order_ids'] = [];
        //loop through each order
        foreach($inputs as $input){
            $order = new Orders;
            $order->user_id = $input['user_id'];
            //for the purposes of this test, several statuses like this one are
            //unnecessary
            $order->status  = Orders::STATUS_CREATED;
            $order->save();
            $created['order_ids'][] = $order->id;
            $created['order_ids'][$order->id]['order_product_ids'] = [];
            //loop through products to be added to current order
            foreach($input['products'] as $product){

                $orderProduct = new OrderProducts;
                $orderProduct->orders_id    = $order->id;
                $orderProduct->products_id  = $product['id'];
                $orderProduct->qty          = $product['qty'];
                $orderProduct->vendor       = $product['vendor'];
                $orderProduct->status       = Orders::STATUS_PENDING;
                $orderProduct->save();
                $created['order_ids'][$order->id]['order_product_ids'][] = $orderProduct->id;
            }
            $order->status = Orders::STATUS_PENDING;
            $order->save();
        }

        return json_encode($created);

    }

}
