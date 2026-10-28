<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories', ['categories' => Category::withCount('products')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:80|unique:categories,name']);
        Category::create($data + ['slug' => $this->slug($data['name'])]);
        return back()->with('ok', __('soko.flash.saved'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:80', Rule::unique('categories', 'name')->ignore($category->id)]]);
        $category->update($data);
        return back()->with('ok', __('soko.flash.saved'));
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', __('soko.flash.cannot_delete_in_use'));
        }
        $category->delete();
        return back()->with('ok', __('soko.flash.deleted'));
    }

    private function slug(string $name): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $i = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
