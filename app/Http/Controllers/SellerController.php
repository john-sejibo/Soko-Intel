<?php
namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\PriceRecord;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function dashboard(Request $request)
    {
        $seller = $request->user()->seller;
        return view('seller.dashboard', [
            'seller'   => $seller,
            'markets'  => Market::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'records'  => $seller ? $seller->priceRecords()->with('product')->latest('recorded_at')->limit(20)->get() : collect(),
        ]);
    }

    public function storeProfile(Request $request)
    {
        $data = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'business_name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:20',
        ]);
        $request->user()->seller()->updateOrCreate(['user_id' => $request->user()->id], $data);
        return back()->with('ok', 'Profile ya muuzaji imehifadhiwa.');
    }

    public function storePrice(Request $request)
    {
        $seller = $request->user()->seller;
        abort_unless($seller, 403, 'Kamilisha profile ya muuzaji kwanza.');

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|integer|min:1|max:1000000000',
            'stock_qty' => 'nullable|integer|min:0',
        ]);
        PriceRecord::create($data + [
            'seller_id' => $seller->id, 'market_id' => $seller->market_id,
            'source' => 'seller', 'recorded_at' => now(),
        ]);
        return back()->with('ok', 'Bei imehifadhiwa.');
    }
}
