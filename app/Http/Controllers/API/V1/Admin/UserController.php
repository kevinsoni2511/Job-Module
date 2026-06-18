<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request) 
    {
        $users = User::query()
            ->when($request->keyword, fn($q) => $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('email', 'like', '%' . $request->keyword . '%'))
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,job_keeper,job_seeker'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created',
            'data' => $user
        ]);
    }

    public function show(int $id) 
    {
        $user = User::with(['company', 'seekerProfile'])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function update(Request $request, int $id) 
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => "sometimes|email|unique:users,email,{$id}",
            'role'     => 'sometimes|in:job_keeper,job_seeker,admin',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['success' => true, 'message' => 'User updated.', 'data' => $user->fresh()]);
    }

    public function destroy(int $id) 
    {
        User::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'User deleted.']);
    }
}