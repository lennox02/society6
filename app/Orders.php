<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    const STATUS_CREATED = 1;
    const STATUS_AWAITING_PAYMENT = 2;
    const STATUS_PENDING = 3;
    const STATUS_VENDOR_REJECTED = 4;
    const STATUS_VENDOR_DELAYED = 5;
    const STATUS_VENDOR_PENDING = 6;
    const STATUS_VENDOR_SHIPPED = 7;
    const STATUS_DELIVERED = 8;

    const VENDOR_STATUSES = [
        self::STATUS_VENDOR_REJECTED,
        self::STATUS_VENDOR_DELAYED,
        self::STATUS_VENDOR_PENDING,
        self::STATUS_VENDOR_SHIPPED,
        self::STATUS_DELIVERED
    ];
}
