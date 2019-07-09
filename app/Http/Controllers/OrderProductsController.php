<?php

namespace App\Http\Controllers;

use App\OrderProducts as OrderProducts;
use App\Repositories\OrderProductsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderProductsController extends Controller
{

    private $orderProductsRepository;

    function __construct(OrderProductsRepository $orderProductsRepository) {
        $this->orderProductsRepository = $orderProductsRepository;
    }

    /*
        EXPECTED JSON FORMAT
        {
            "order_products":[
                {
                    "id":"1234",
                    "status":"5" //delayed
                },
                {
                    "id":"1235",
                    "status":"7" //shipped
                }
            ]
        }
    */
    public function updateOrderProducts( Request $request ){

        $orderProducts = $request->input('order_products');

        $str = "";

        DB::beginTransaction();

        foreach($orderProducts as $orderProduct){

            //reverse all updates if any data is bad (if even one order id
            //or status isn't found, no data can be trusted)
            if(is_numeric($orderProduct['id']) && is_numeric($orderProduct['status'])){

                try {
                    $op = $this->orderProductsRepository->getById($orderProduct['id']);
                    $op->status = $orderProduct['status'];
                    $op->save();
                } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    DB::rollBack();
                    echo 'BAD DATA.  Order Product ID ' . $orderProduct['id'] .
                         ' NOT FOUND.  No DATA Updated';
                }

            } else {
                DB::rollBack();
                return 'BAD DATA.  Order Product ID "' . $orderProduct['id'] .
                       '" or Order Product Status "' . $orderProduct['status'] .
                       '" is not a number';
            }

        }

        DB::commit();

        return "Orders Updated";

    }

}
