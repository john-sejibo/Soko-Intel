<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->withCount('priceRecords')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")->orderBy('name')->paginate(30);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->data($request);
        Product::create($data + ['slug' => Product::makeSlug($data['name']), 'status' => 'approved', 'created_by' => $request->user()->id]);
        return redirect()->route('admin.products.index')->with('ok', __('soko.flash.saved'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->data($request));
        return redirect()->route('admin.products.index')->with('ok', __('soko.flash.saved'));
    }

    public function destroy(Product $product)
    {
        if ($product->priceRecords()->exists()) {
            return back()->with('error', __('soko.flash.cannot_delete_in_use'));
        }
        $product->delete();
        return back()->with('ok', __('soko.flash.deleted'));
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);
        return back()->with('ok', __('soko.flash.saved'));
    }

    private function data(Request $request): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'brand' => 'nullable|string|max:80',
            'model' => 'nullable|string|max:80',
        ]);
    }
}
