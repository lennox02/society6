<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class VendorController extends Controller
{
    /*
        NOTE to reviewers

        The plan was to move getPendingOrders from DreamJunctionController
        and MacroFineArtsController to here, and generally try to abstract as
        much as possible, by I fell short on time.

    */

    public function getPendingOrders();
    abstract public function formatOrder();
    abstract public function formatOrderItems();
}
