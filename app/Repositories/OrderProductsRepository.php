<?php
namespace App\Repositories;

use App\OrderProducts as OrderProducts;
use App\Repositories\Interfaces\OrderProductsRepositoryInterface;


class OrderProductsRepository implements OrderProductsRepositoryInterface {

    public function getById(int $id){
        return  OrderProducts::findOrFail($id);
    }

}


?>
