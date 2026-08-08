<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    /**
     * عرض المستخدمين مع أدوارهم
     */
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * تغيير الدور مباشرة من صفحة index
     */
    public function change(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // رول واحد فقط لكل يوزر
        $user->syncRoles([$request->role]);

        // رجوع لنفس الصفحة (بدون view مباشرة)
        return redirect()->route('users.index');
    }
}