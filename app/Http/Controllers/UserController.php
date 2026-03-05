<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     * Requires 'edit articles' permission to create users.
     */
    public function store(Request $request)
    {
        if (!$request->user()->can('edit articles')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     * Requires 'edit articles' permission to update users.
     */
    public function update(Request $request, string $id)
    {
        if (!$request->user()->can('edit articles')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'phone', 'address']));
        return response()->json([
            'message' => 'User updated!',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * Requires 'delete articles' permission to delete users.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('delete articles')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
