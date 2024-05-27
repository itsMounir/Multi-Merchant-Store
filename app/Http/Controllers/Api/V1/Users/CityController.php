<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\CityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{


    /*public function index()
    {
        $this->authorize('viewAny', City::class);
        $parent_cities = City::where('parent_id', null)->orderBy('position', 'asc')->get();
        $child_cities = City::with('parent')->where('parent_id', '!=', null)->orderBy('position', 'asc')->get();
        $cities = [
            'parent' => $parent_cities,
            'child' => $child_cities
        ];
        return response()->json($cities);
    }*/

    /**
     * TO get all cities
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', City::class);
        $city = City::with('Childrens')->where('parent_id', null)->get();
        return response()->json($city, 200);
    }
    /**
     *public function index()
     *{}
     *    $this->authorize('viewAny', City::class);
     *   $parent_cities = City::where('parent_id', null)->orderBy('name', 'asc')->get();
     *  $child_cities = City::with('parent')->where('parent_id', '!=', null)->orderBy('name', 'asc')->get();
     * $cities = [
     *    'parent' => $parent_cities,
     *   'child' => $child_cities
     * ];
     * return response()->json($cities);
     * }
     */
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
     * reorder cities position
     */
    public function reorder(Request $request)
    {
        $this->authorize('create', City::class);

        $cityIds = $request->input('city_ids');

        foreach ($cityIds as $position => $id) {
            City::where('id', $id)->update(['position' => $position]);
        }
        $cities = $this->index();
        return response()->json($cities, 200);
    }

     /**
     * update position to specific city
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePosition(String $id, Request $request)
    {
        $city = City::findOrFail($id);
        $this->authorize('update', $city);
        $city->update(['position' => $request->position]);
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
