<?php
namespace App\Repositories;
use App\Models\Creatives;
use App\OrderProducts as OrderProducts;

class OrderProductsRepository {

    public function getById(int $id){
        return  OrderProducts::findOrFail($id);
    }

}


?>
