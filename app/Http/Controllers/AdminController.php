<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }


        $admins = $query->get();

        return view('admin.admins.index', compact('admins'));
    }

    // {
    //     $admins = Admin::all();
    //     return view('admin.admins.index', compact('admins'));
    //     //  dd('loma');
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    // Store new admin
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {

         return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        // $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
        // dd($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8',
        ]);

        // Update admin info
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admins.index')
            ->with('success', 'Admin updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        // Check if admin user is logged in using your session approach
        $currentAdminId = session('admin_id');

        // If there's no authenticated admin or attempting to delete your own account
        if (!$currentAdminId || $currentAdminId === $admin->id) {
            return redirect()->route('admins.index')
                ->with('error', $currentAdminId ? 'You cannot delete your own account.' : 'Authentication required.');
        }

        $admin->delete();

        return redirect()->route('admins.index')
            ->with('success', 'Admin deleted successfully!');
    }
    public function showLoginForm()
{
    return view('admin.auth.login');
}


public function login(Request $request)
{
    //  dd('hello luma');
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Attempt to find the admin by email
    $admin = Admin::where('email', $request->email)->first();

    // Check if admin exists and password matches
    if ($admin && Hash::check($request->password, $admin->password)) {
        // Create admin session
        $request->session()->put('admin_id', $admin->id);
        $request->session()->put('admin_name', $admin->name);

        return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $admin->name);
    }

    // Failed login
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->withInput($request->only('email'));
}

/**
 * Log the admin out
 */
public function logout(Request $request)
{
    $request->session()->forget(['admin_id', 'admin_name']);

    return redirect()->route('admin.login')->with('success', 'You have been logged out successfully');
}
}
