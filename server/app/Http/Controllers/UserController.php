<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $user = Auth::user();

        $user->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully!'], 200);
    }

    // Get User
    public function getUser(Request $request)
    {
        $user = Auth::user(); 

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
public function updateUser(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $validatedData = $request->validate([
        'profile' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'mobile' => 'required|string|max:15',
        'company_position' => 'required|string|max:255',
    ]);

    if ($request->hasFile('profile')) {
        $imagePath = $request->file('profile')->store('profiles', 'public');
        $user->profile = $imagePath;
    }

    // Update fields
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];
    $user->mobile = $validatedData['mobile'];
    $user->company_position = $validatedData['company_position'];

    $user->save();

    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
}




    // Delete User
    public function deleteUser(Request $request)
    {
        $user = Auth::user();

        $user->delete();

        return response()->json(['message' => 'User deleted successfully!'], 200);
    }
}
