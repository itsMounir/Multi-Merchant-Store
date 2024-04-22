<?php

namespace App\Filters\Markets;


use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class BillsFilters extends BaseFilter
{
    public function status(Builder $query): Builder
    {
        if ($this->request->input('status') == 'حالية') {
            return $query->where('status', 'انتظار')->orWhere('status','جديد')->orWhere('status','قيد التحضير');
        }
        elseif ($this->request->input('status') == 'سابقة'){
            return $query->where('status', 'ملغية')->orWhere('status','تم التوصيل')->orWhere('status','رفض الاستلام');
        }
        return $query;
    }
}
