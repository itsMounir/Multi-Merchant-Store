<?php

namespace App\Http\Controllers\api\v1\users;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\users\CityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{

    /**
     * TO get Cities 
     * @return JsonResponse
     */
    public function index()
    {
        $cities = City::with('parnet')->orderBy('name', 'desc')->get();
        return response()->josn($cities);
    }

    /**
     * To add new city
     * @param CityRequest $request
     * @return JsonResponse
     */
    public function create(CityRequest $request)
    {
        $city = City::create($request->all());
        return response()->jsone($city, 201);
    }
    /**
     * To edit city information
     * @param CityRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(CityRequest $request, $id)
    {
        $city = City::findOrFail($id);
        $city->update($request->all());
        return response()->json($city, 200);
    }
    /**
     * To delete city
     * @param string $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return response()->json(null, 204);
    }
}
