<?php
namespace App\Repositories\Interfaces;
use App\User;
interface VendorRepositoryInterface
{
    public function getPendingOrders(int $vendor, int $status);
    public function getPendingOrderProducts(int $vendor, int $status);
}
