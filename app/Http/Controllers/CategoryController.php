<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\CategoriesExport;
// use Barryvdh\DomPDF\Facade\Pdf;


class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $query = \App\Models\Category::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $categories = $query->get();

        return view('admin.categories.index', compact('categories'));
    }





    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate(['name' => 'required|string|max:255']);
    //     $slug = Str::slug($request->name);

    //     Category::create([
    //         'name' => $request->name,
    //         'slug' => $slug,
    //     ]);

    //     return redirect()->route('categories.index');
    // }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }



    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }


    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }


    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
//     public function exportExcel()
// {
//     return Excel::download(new CategoriesExport, 'categories.xlsx');
// }


// public function exportPDF()
// {
//     $categories = Category::all();
//     $pdf = Pdf::loadView('admin.categories.pdf', compact('categories'));
//     return $pdf->download('categories.pdf');
// }

}
