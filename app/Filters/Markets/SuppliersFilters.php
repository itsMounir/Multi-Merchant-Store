<?php

namespace App\Filters\Markets;


use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class SuppliersFilters extends BaseFilter
{
    public function type(Builder $query): Builder
    {
        return $query->whereHas('supplierCategory',function ($query) {
            return $query->where('type',$this->request->input('type'));
        });
    }
}
