<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Market;
use App\Models\PriceRecord;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $shops = $user->sellers()->with('market')->orderBy('business_name')->get();

        return view('seller.dashboard', [
            'shops'    => $shops,
            'products' => $this->productOptions($user->id),
            'records'  => PriceRecord::with(['product', 'seller'])->whereIn('seller_id', $shops->pluck('id'))
                            ->latest('recorded_at')->limit(15)->get(),
        ]);
    }

    /* ---------- Biashara / maduka ---------- */

    public function createShop(Request $request)
    {
        return view('seller.shop-form', ['shop' => new Seller(), 'markets' => Market::visibleTo($request->user()->id)->orderBy('name')->get()]);
    }

    public function storeShop(Request $request)
    {
        $request->user()->sellers()->create($request->validate($this->shopRules($request->user()->id)));
        return redirect()->route('seller.dashboard')->with('ok', __('soko.flash.shop_saved'));
    }

    public function editShop(Request $request, Seller $shop)
    {
        $this->own($request, $shop);
        return view('seller.shop-form', ['shop' => $shop, 'markets' => Market::visibleTo($request->user()->id)->orderBy('name')->get()]);
    }

    public function updateShop(Request $request, Seller $shop)
    {
        $this->own($request, $shop);
        $shop->update($request->validate($this->shopRules($request->user()->id)));
        return redirect()->route('seller.dashboard')->with('ok', __('soko.flash.shop_saved'));
    }

    /* ---------- Pendekeza soko jipya ---------- */

    public function createMarket()
    {
        return view('seller.market-form');
    }

    public function storeMarket(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'area' => 'nullable|string|max:120',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90|required_with:longitude',
            'longitude' => 'nullable|numeric|between:-180,180|required_with:latitude',
        ]);
        Market::create($data + ['status' => 'pending', 'created_by' => $request->user()->id]);
        return redirect()->route('seller.shops.create')->with('ok', __('soko.flash.market_proposed'));
    }

    /* ---------- Pendekeza bidhaa mpya ---------- */

    public function createProduct()
    {
        return view('seller.product-form', ['categories' => Category::orderBy('name')->get()]);
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'brand' => 'nullable|string|max:80',
            'model' => 'nullable|string|max:80',
        ]);
        Product::create($data + ['slug' => Product::makeSlug($data['name']), 'status' => 'pending', 'created_by' => $request->user()->id]);
        return redirect()->route('seller.dashboard')->with('ok', __('soko.flash.product_proposed'));
    }

    /* ---------- Bei ---------- */

    public function storePrice(Request $request)
    {
        $uid = $request->user()->id;
        $data = $request->validate([
            'shop_id' => 'required|integer',
            'product_id' => ['required', Rule::exists('products', 'id')->where(fn ($q) => $q->where('status', 'approved')->orWhere('created_by', $uid))],
            'price' => 'required|integer|min:1|max:1000000000',
            'stock_qty' => 'nullable|integer|min:0|max:10000000',
        ]);
        $shop = $request->user()->sellers()->where('status', 'active')->findOrFail($data['shop_id']);

        PriceRecord::create([
            'product_id' => $data['product_id'], 'seller_id' => $shop->id, 'market_id' => $shop->market_id,
            'price' => $data['price'], 'stock_qty' => $data['stock_qty'] ?? null,
            'source' => 'seller', 'recorded_at' => now(),
        ]);
        return back()->with('ok', __('soko.flash.price_saved'));
    }

    /* ---------- Msaada ---------- */

    private function own(Request $request, Seller $shop): void
    {
        abort_unless($shop->user_id === $request->user()->id, 403);
    }

    private function productOptions(int $uid)
    {
        return Product::where(fn ($q) => $q->where('status', 'approved')->orWhere('created_by', $uid))->orderBy('name')->get();
    }

    private function shopRules(int $uid): array
    {
        return [
            'business_name' => 'required|string|max:120',
            'market_id' => ['required', Rule::exists('markets', 'id')->where(fn ($q) => $q->where('status', 'approved')->orWhere('created_by', $uid))],
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'opening_hours' => 'nullable|string|max:120',
            'description' => 'nullable|string|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90|required_with:longitude',
            'longitude' => 'nullable|numeric|between:-180,180|required_with:latitude',
        ];
    }
}
