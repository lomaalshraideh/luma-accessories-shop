<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if admin session exists
        if (!$request->session()->has('admin_id')) {
            return redirect()->route('admin.login.form')
                ->with('error', 'Please login to access the admin area');
        }

        // Get admin from session
        $admin = Admin::find($request->session()->get('admin_id'));

        // If admin not found, clear session and redirect
        if (!$admin) {
            $request->session()->forget(['admin_id', 'admin_name']);
            return redirect()->route('admin.login.form')
                ->with('error', 'Admin account not found');
        }

        // Admin found, proceed
        return $next($request);
    }
}
