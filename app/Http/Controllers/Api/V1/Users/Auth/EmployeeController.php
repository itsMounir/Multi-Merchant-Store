<?php

namespace App\Http\Controllers\Api\V1\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\users\Auth\CreateAccountRequest;
use App\Http\Requests\api\v1\users\auth\UpdateEmployeeRequest;
use Spatie\Permission\Traits\HasRoles;

class EmployeeController extends Controller
{
    use HasRoles;
    public function create(CreateAccountRequest $request)
    {
        $this->authorize('create', User::class);

        $user = User::create($request->all());
        $user->assignRole($request->role);
        return response()->json(['message' => 'Account has been created successfully ', 'User' => $user], 201);
    }
    /**
     * To get all employees filterd with roles
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewany', User::class);
        
        $role = $request->query('role');
        if ($role)
            $employees = User::role($role)->get();
        else {
            $employees = User::with('roles')->get();
        }
        return response()->json($employees, 200);
    }

    /**
     * To get employee by id
     * @param string $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $employee = User::with('roles')->findOrFail($id);
        $this->authorize('view', $employee);
        $roles = $employee->getRoleNames();
        return response()->json($employee, 200);
    }
    /**
     * To update Employee information
     * @param string $id
     * @param CreateAccountRequest $request
     * @return JsonResponse
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = User::with('roles')->findOrFail($id);
        $this->authorize('update', $employee);

        $employee->update($request->all());
        $employee->syncRoles($request->role);
        return response()->json(['message' => 'User has been updated successfully', 'user' => $employee], 200);
    }

    /**
     * To delete Employee
     * @param string $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        $this->authorize('delete', $employee);

        $employee->delete();
        return response()->json(['message' => 'User has been deleted successfully'], 204);
    }
}
