<?php
namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\PriceReport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function create()
    {
        return view('reports.create', [
            'products' => Product::where('status', 'approved')->orderBy('name')->get(),
            'markets' => Market::approved()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', Rule::exists('products', 'id')->where('status', 'approved')],
            'market_id' => ['required', Rule::exists('markets', 'id')->where('status', 'approved')],
            'price' => 'required|integer|min:1|max:1000000000',
        ]);
        PriceReport::create($data + ['user_id' => $request->user()->id]);
        return redirect('/')->with('ok', __('soko.flash.report_sent'));
    }
}
