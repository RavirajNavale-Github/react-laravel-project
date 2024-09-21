<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $request->validate([
            'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:120',
            'email' => 'required|string|email|max:120|unique:users',
            'mobile' => 'required|string|regex:/^\+91\d{10}$/|unique:users',
            'company_position' => 'required|string|max:120',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Handle the file upload
        $profilePath = null;
        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'company_position' => $request->company_position,
            'password' => Hash::make($request->password),
            'profile' => $profilePath,
        ]);

        return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
    }

    // Login User
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a token for the user
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json(['message' => 'Login successful!', 'token' => $token], 200);
    }

    // Logout User
    public function logout(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Revoke the token
        $user->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully!'], 200);
    }

    // Get User
    public function getUser(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Return user information without password
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'company_position' => $user->company_position,
            'profile' => $user->profile,
        ]);
    }

    // Update User
    public function updateUser(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        $request->validate([
            'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'string|max:120',
            'email' => 'string|email|max:120|unique:users,email,' . $user->id,
            'mobile' => 'string|regex:/^\+91\d{10}$/|unique:users,mobile,' . $user->id,
            'company_position' => 'string|max:120',
        ]);

        // Update fields
        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
            $user->profile = $profilePath;
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('mobile')) {
            $user->mobile = $request->mobile;
        }

        if ($request->has('company_position')) {
            $user->company_position = $request->company_position;
        }

        $user->save(); // Save the updated user information

        return response()->json(['message' => 'User updated successfully!', 'user' => $user], 200);
    }

    // Delete User
    public function deleteUser(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        $user->delete(); // Delete the user from the database

        return response()->json(['message' => 'User deleted successfully!'], 200);
    }
}
