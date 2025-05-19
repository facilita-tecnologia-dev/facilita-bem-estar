<?php

namespace App\Http\Controllers;

use App\Models\UserDepartmentPermission;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Company;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $user = $request->user();
        
        $companyDepartments = Company::firstWhere('id', session('company')->id)
            ->users()
            ->pluck('department')
            ->unique()
            ->values()
            ->sortBy(function ($department) use ($user) {
                return $department === $user->department ? 0 : 1;
            })
            ->values();

        $userDepartmentPermissions = UserDepartmentPermission::where('company_id', session('company')->id)
            ->where('user_id', $user->id)
            ->orderByRaw("CASE WHEN department = ? THEN 0 ELSE 1 END", [$user->department])
            ->orderBy('allowed', 'desc')
            ->get();

        dd([
            'user_department' => $user->department,
            'permissions' => $userDepartmentPermissions->toArray()
        ]);

        return view('users.index', compact('userDepartmentPermissions'));
    }
} 