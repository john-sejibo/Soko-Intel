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
            ->when($request->q, function ($q, $s) {
                $like = '%'.mb_strtolower($s).'%';
                $q->where(fn ($w) => $w
                    ->whereRaw('LOWER(name) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(brand) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(model) LIKE ?', [$like]));
            })
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->orderBy('name')->paginate(12)->withQueryString();

        return view('home', ['products' => $products, 'categories' => Category::orderBy('name')->get()]);
    }
}
