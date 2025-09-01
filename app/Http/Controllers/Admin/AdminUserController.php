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
        $users = User::ownedByUser()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $currentUser = Auth::user();
        $availableRoles = User::getAvailableRoles();
        
        return view('admin.users.create', compact('availableRoles'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $availableRoles = User::getAvailableRoles();
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:' . implode(',', $availableRoles)
        ]);
        
        $data['owner_id'] = Auth::user()->owner_id;
        $data['password'] = bcrypt($data['password']);
        
        User::create($data);
        
        return redirect()->route('admin.users.index')->with('success', __('User created successfully.'));
    }

    public function edit(User $user)
    {
        $currentUser = Auth::user();
        
        $availableRoles = User::getAvailableRoles();

        return view('admin.users.edit', compact('user', 'availableRoles'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        $availableRoles = User::getAvailableRoles();
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:' . implode(',', $availableRoles)
        ]);
        
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role']
        ];
        
        if (!empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }
        
        $user->update($updateData);
        
        return redirect()->route('users.index')->with('success', __('User updated successfully.'));
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
    
    private function getAvailableRolesForUser(User $user): array
    {
        $hierarchy = User::getRoleHierarchy();
        return $hierarchy[$user->role] ?? [];
    }
}
