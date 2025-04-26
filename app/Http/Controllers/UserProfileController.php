<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {
        $profiles = UserProfile::with('user')->get();
        return view('user_profiles.index', compact('profiles'));
    }

    public function create()
    {
        $users = User::all();
        return view('user_profiles.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'avatar' => 'nullable|string',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        UserProfile::create($request->all());

        return redirect()->route('user-profiles.index')->with('success', 'Profile created!');
    }

    public function show(UserProfile $userProfile)
    {
        return view('user_profiles.show', compact('userProfile'));
    }

    public function edit(UserProfile $userProfile)
    {
        $users = User::all();
        return view('user_profiles.edit', compact('userProfile', 'users'));
    }

    public function update(Request $request, UserProfile $userProfile)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'avatar' => 'nullable|string',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $userProfile->update($request->all());

        return redirect()->route('user-profiles.index')->with('success', 'Profile updated!');
    }

    public function destroy(UserProfile $userProfile)
    {
        $userProfile->delete();
        return redirect()->route('user-profiles.index')->with('success', 'Profile deleted!');
    }
}
