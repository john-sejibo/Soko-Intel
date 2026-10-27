<?php
namespace App\Http\Controllers;

use App\Models\PriceRecord;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        // Bei ya hivi karibuni kwa kila soko
        $latest = PriceRecord::with(['market', 'seller'])
            ->where('product_id', $product->id)
            ->orderByDesc('recorded_at')->get()
            ->unique('market_id')->sortBy('price')->values();

        // Historia ya bei: wastani wa kila siku (siku 30)
        $history = PriceRecord::where('product_id', $product->id)
            ->where('recorded_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(recorded_at) as d, AVG(price) as avg_price, MIN(price) as min_price')
            ->groupBy('d')->orderBy('d')->get();

        // Mabadiliko: wastani siku 7 za mwisho dhidi ya siku 7 zilizotangulia
        $current  = PriceRecord::where('product_id', $product->id)->where('recorded_at', '>=', now()->subDays(7))->avg('price');
        $previous = PriceRecord::where('product_id', $product->id)
            ->whereBetween('recorded_at', [now()->subDays(14), now()->subDays(7)])->avg('price');
        $change = ($current && $previous) ? round((($current - $previous) / $previous) * 100, 1) : null;

        return view('products.show', compact('product', 'latest', 'history', 'change'));
    }
}
