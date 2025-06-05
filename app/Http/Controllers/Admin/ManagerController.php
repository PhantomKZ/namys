<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ManagerController extends Controller
{
    /**
     * Display a listing of the managers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();
        return view('admin.managers.index', compact('users'));
    }

    /**
     * Update the role of a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, User $user)
    {
        Log::info('Attempting to update role for user ID: ' . $user->id);
        Log::info('Request data: ' . json_encode($request->all()));

        $request->validate([
            'role' => ['required', 'in:client,manager,admin'],
        ]);

        // Удаляем все текущие роли пользователя
        $user->syncRoles([]);

        // Назначаем новую роль
        $user->assignRole($request->role);

        Log::info('Role updated successfully for user ID: ' . $user->id . ' to role: ' . $request->role);

        return redirect()->route('admin.managers.index')->with('success', 'Роль пользователя обновлена успешно!');
    }
} 