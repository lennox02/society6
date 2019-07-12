<?php
namespace App\Repositories\Interfaces;
use App\User;
interface OrderProductsRepositoryInterface
{
    public function getById(int $id);
}
