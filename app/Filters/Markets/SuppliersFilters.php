<?php

namespace App\Filters\Markets;


use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class SuppliersFilters extends BaseFilter
{
    public function category(Builder $query): Builder
    {
        return $query->whereHas('category',function ($query) {
            return $query->where('type',$this->request->input('category'));
        });
    }
}
