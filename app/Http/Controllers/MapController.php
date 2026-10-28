<?php
namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\PriceRecord;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $product = $request->filled('product')
            ? Product::where('slug', $request->product)->where('status', 'approved')->first() : null;

        $prices = $product
            ? PriceRecord::where('product_id', $product->id)->orderByDesc('recorded_at')->get()->unique('market_id')->pluck('price', 'market_id')
            : collect();

        $markets = Market::approved()->whereNotNull('latitude')->whereNotNull('longitude')->get()
            ->map(fn ($m) => [
                'name' => $m->name, 'area' => $m->area, 'lat' => $m->latitude, 'lng' => $m->longitude,
                'price' => $prices[$m->id] ?? null, 'url' => $m->gmapsUrl(),
            ])->values();

        $shops = Seller::with('market')->where('status', 'active')->whereNotNull('latitude')->whereNotNull('longitude')->get()
            ->map(fn ($s) => [
                'name' => $s->business_name, 'market' => $s->market?->name, 'lat' => $s->latitude, 'lng' => $s->longitude,
                'phone' => $s->phone, 'verified' => $s->is_verified, 'url' => $s->gmapsUrl(),
            ])->values();

        return view('map', [
            'markets' => $markets, 'shops' => $shops, 'product' => $product,
            'products' => Product::where('status', 'approved')->orderBy('name')->get(),
        ]);
    }
}
