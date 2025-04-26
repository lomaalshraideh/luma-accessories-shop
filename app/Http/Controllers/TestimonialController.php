<?php
namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('user')->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $users = User::all();
        return view('testimonials.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'message' => 'required|string',
            'image' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Testimonial::create($request->all());

        return redirect()->route('testimonials.index')->with('success', 'Testimonial added!');
    }

    public function show(Testimonial $testimonial)
    {
        return view('testimonials.show', compact('testimonial'));
    }

    public function edit(Testimonial $testimonial)
    {
        $users = User::all();
        return view('testimonials.edit', compact('testimonial', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,rejected',
        ]);

        $testimonial = \App\Models\Testimonial::findOrFail($id);

        // This is the line you asked about:
        $testimonial->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Testimonial status updated successfully.');
    }



    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('testimonials.index')->with('success', 'Testimonial deleted!');
    }
}

