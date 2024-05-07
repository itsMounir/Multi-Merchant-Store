<?php

namespace App\Http\Controllers\api\v1\users;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\users\CityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{

    /**
     * TO get all cities 
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', City::class);
        $parent_cities = City::where('parent_id', null)->orderBy('name', 'asc')->get();
        $child_cities = City::with('parent')->where('parent_id', '!=', null)->orderBy('name', 'asc')->get();
        $cities = [
            'parent' => $parent_cities,
            'child' => $child_cities
        ];
        return response()->json($cities);
    }

    /**
     * To create new city
     * @param CityRequest $request
     * @return JsonResponse
     */
    public function create(CityRequest $request)
    {
        $this->authorize('create', City::class);

        $city = City::create($request->all());
        return response()->json($city, 201);
    }
    /**
     * To edit city information
     * @param CityRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(CityRequest $request, String $id)
    {
        $city = City::findOrFail($id);
        $this->authorize('update', $city);

        $city->update($request->all());
        return response()->json($city, 200);
    }
    /**
     * To delete city
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(String $id)
    {
        $city = City::findOrFail($id);
        $this->authorize('delete', $city);

        $city->delete();
        return response()->json(null, 204);
    }
}
