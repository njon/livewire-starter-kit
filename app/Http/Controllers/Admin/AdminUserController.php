<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('owner_id', Auth::user()->owner_id)->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:admin,user,partner'
        ]);
        
        $data['owner_id'] = Auth::user()->owner_id;
        $data['password'] = bcrypt($data['password']);
        $data['role'] = $data['role'] ?? 'user';
        
        User::create($data);
        
        return redirect()->route('admin.users.index')->with('success', __('User created successfully.'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string|in:admin,user,partner'
        ]);
        
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email']
        ];
        
        if (!empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }
        
        if (isset($data['role'])) {
            $updateData['role'] = $data['role'];
        }
        
        $user->update($updateData);
        
        return redirect()->route('admin.users.index')->with('success', __('User updated successfully.'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Prevent deleting the currently logged-in user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => __('You cannot delete your own account.')]);
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', __('User deleted successfully.'));
    }
}
