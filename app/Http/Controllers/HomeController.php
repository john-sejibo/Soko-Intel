<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->withMin(['priceRecords as min_price' => fn ($q) => $q->where('recorded_at', '>=', now()->subDays(30))], 'price')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$s}%")->orWhere('brand', 'like', "%{$s}%")->orWhere('model', 'like', "%{$s}%")))
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->orderBy('name')->paginate(12)->withQueryString();

        return view('home', ['products' => $products, 'categories' => Category::orderBy('name')->get()]);
    }
}
