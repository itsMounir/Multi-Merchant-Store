<?php

namespace App\Http\Controllers\Api\V1\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Users\Auth\CreateAccountRequest;
use App\Http\Requests\Api\V1\Users\Auth\UpdateEmployeeRequest;
use App\Http\Requests\Api\V1\Users\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;


class EmployeeController extends Controller
{
    use HasRoles;
    public function create(CreateAccountRequest $request)
    {
        $this->authorize('create', User::class);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);
        $user->load('roles:name', 'permissions');

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

        $employee->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $employee->syncRoles($request->role);
        $employee->load('roles:name', 'permissions');

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

        $employee->tokens()->delete();
        $employee->delete();
        
        return response()->json(['message' => 'User has been deleted successfully'], 204);
    }

    /**
     * Change Employee password
     * @param ChangePasswordRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request, String $id)
    {
        $employee = User::findOrFail($id);
        $this->authorize('update', $employee);
        $employee->password =  Hash::make($request->password);
        $employee->save();
        return response()->json(['message' => 'password changed']);
    }
}
