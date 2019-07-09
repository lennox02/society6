<?php
namespace App\Repositories;
use App\Models\Creatives;

class CreativesRepository {

    public function getById(int $id){
        return Creatives::find($id);
    }

}


?>
