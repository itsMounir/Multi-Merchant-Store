<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{

    Bill,
    Supplier,
    Market,
    Notification
};


use Illuminate\Support\Facades\{
    Auth,
    DB
};

class NotificationController extends Controller
{



    public function index(){
        $supplier = Auth::user();

        $notifications = $supplier->getNotifications();
        $supplier->unreadNotifications->markAsRead();

        return $this->indexOrShowResponse('body',$notifications);
    }

}
