<?php

namespace App\Filters\Markets;


use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class BillsFilters extends BaseFilter
{
    public function status(Builder $query): Builder
    {
        return $query->where('status', $this->request->input('status'));
    }
}
