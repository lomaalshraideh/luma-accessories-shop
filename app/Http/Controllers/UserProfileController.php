<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gender' => 'nullable|in:male,female,other',
        ]);

        UserProfile::create($request->all());

        return redirect()->route('main.user_profile')->with('success', 'Profile created!');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Get the authenticated user
        $user = auth()->user();

        // If user doesn't have a profile, create one
        if (!$user->profile) {
            $profile = new \App\Models\UserProfile(['user_id' => $user->id]);
            $profile->save();
            $user->refresh();
        }

        return view('main.user_profile', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
        $users = User::all();
        return view('user_profiles.edit', compact('userProfile', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:500',
        ]);

        // Update user data
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();

        // Create or update profile
        $profile = $user->profile ?? new \App\Models\UserProfile(['user_id' => $user->id]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = $avatarPath;
        }

        $profile->birth_date = $request->birth_date;
        $profile->gender = $request->gender;
        $profile->bio = $request->bio;
        $profile->save();

        return redirect()->route('main.user_profile')->with('success', 'Profile updated successfully.');
    }
}
