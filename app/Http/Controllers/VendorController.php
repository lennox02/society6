<?php

namespace App\Http\Controllers;

use App\OrderProducts as OrderProducts;
use App\Orders as Orders;
use App\Users as Users;
use App\Repositories\VendorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use stdClass;

abstract class VendorController extends Controller
{

    const VENDOR_ID = 0;

    private $vendorRepository;

    function __construct(VendorRepository $vendorRepository) {
        $this->vendorRepository = $vendorRepository;
    }

    public function getPendingOrders(){

        $pendingOrders = $this->vendorRepository->getPendingOrders(static::VENDOR_ID, Orders::STATUS_PENDING);

        if(count($pendingOrders)){
            $pendingOrderProducts = $this->vendorRepository->getPendingOrderProducts(static::VENDOR_ID, Orders::STATUS_PENDING);
            return $this->formatOrders($pendingOrders, $pendingOrderProducts);
        } else {
            return '';
        }
    }

    abstract public function formatOrders(Collection $orders, Collection $orderProducts);
    abstract public function formatOrder(stdClass $order, array $orderProducts);
    abstract public function formatOrderItems(array $orderProducts);
}
