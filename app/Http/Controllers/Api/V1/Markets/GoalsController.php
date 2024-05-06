<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalsController extends Controller
{
    /**
     * get goals achieved by the aurhenticated market.
     */
    public function __invoke(Request $request)
    {
        $goals = Auth::user()->goals()->get();
        return $this->indexOrShowResponse('goals', $goals);
    }
}
