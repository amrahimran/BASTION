<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ActivityLogger; // <-- import your logger

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:dns|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ], [
            'email.unique' => 'This email is already registered. Please use a different email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.email.dns' => 'The email domain is not valid. Please use a real domain (e.g., gmail.com).'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        // Log activity
        ActivityLogger::log(
            'User Created',
            "Admin created user: {$user->name} ({$user->email}) with role {$user->role}"
        );

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldRole = $user->role;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);

        // Log activity
        ActivityLogger::log(
            'User Updated',
            "Admin updated user: {$oldName} ({$oldEmail}) [Role: {$oldRole}] → {$user->name} ({$user->email}) [Role: {$user->role}]"
        );

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $userEmail = $user->email;
        $userRole = $user->role;

        $user->delete();

        // Log activity
        ActivityLogger::log(
            'User Deleted',
            "Admin deleted user: {$userName} ({$userEmail}) with role {$userRole}"
        );

        return back()->with('success', 'User removed.');
    }
}
