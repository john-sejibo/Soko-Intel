<?php
namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\PriceReport;
use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create()
    {
        return view('reports.create', ['products' => Product::orderBy('name')->get(), 'markets' => Market::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'market_id' => 'required|exists:markets,id',
            'price' => 'required|integer|min:1|max:1000000000',
        ]);
        PriceReport::create($data + ['user_id' => $request->user()->id]);
        return redirect('/')->with('ok', 'Asante! Ripoti yako itahakikiwa kabla ya kuingia kwenye data.');
    }
}
