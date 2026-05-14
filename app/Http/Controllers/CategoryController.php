<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'color'   => $request->color,
        ]);

        return redirect()->route('categories.index');
    }

    public function update(Request $request, Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);

        $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $category->update($request->only('name', 'color'));

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);

        $category->delete();

        return redirect()->route('categories.index');
    }
}
