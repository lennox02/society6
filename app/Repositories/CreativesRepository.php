<?php
namespace App\Repositories;

use App\Models\Creatives;
use App\Repositories\Interfaces\CreativesRepositoryInterface;

class CreativesRepository implements CreativesRepositoryInterface {

    public function getById(int $id){
        return Creatives::find($id);
    }

}


?>
