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
        return $this->indexOrShowResponse('body',$notifications);
    }

    public function show($id){
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return $this->sudResponse('تم قراءة الاشعار بنجاح');
    }

}
