<?php

namespace App\Http\Controllers\Api\V1\Users\Auth;

use App\Http\Requests\Api\V1\users\Auth\CreateAccountRequest;
use App\Notifications\verfication_code;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function create(CreateAccountRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $user = User::create($request->all());
            $user->assignRole('employee');
            //$user->notify(new  verfication_code($verfication_code));
            return response()->json(['message' => 'Account has been created successfully ', 'User' => $user], 201);
        });
    }
    /**
     * To get all employees
     * @return JsonResponse
     */
    public function index()
    {
        $employees = User::where('role_id', 3)->get();
        return response()->json($employees, 200);
    }

    /**
     * To get employee by id
     * @param string $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $employee = User::findOrFail($id);
        return response()->json($employee, 200);
    }
    /**
     * To update Employee information
     * @param string $id
     * @param CreateAccountRequest $request
     * @return JsonResponse
     */
    public function update(CreateAccountRequest $request, $id)
    {

        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json(['message' => 'User has been updated successfully', 'user' => $user], 200);
    }

    /**
     * To delete Employee
     * @param string $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User has been deleted successfully'], 204);
    }
}
