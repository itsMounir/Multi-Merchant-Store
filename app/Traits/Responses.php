<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\{
    Collection,
    Model
};
use Illuminate\Http\JsonResponse;

trait Responses {

    // sud = store, update, destroy
    public function sudResponse(string $message, int $code = 200) : JsonResponse {
        return response()->json([
            'message' => $message
        ], $code);
    }

    public function indexOrShowResponse(string $data_key, Collection|Model|int|array $data, int $code = 200) : JsonResponse {
        return response()->json([
            $data_key => $data
        ], $code);
    }
}
