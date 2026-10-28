<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::withCount(['sellers', 'priceRecords'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")->orderBy('name')->get();
        return view('admin.markets.index', compact('markets'));
    }

    public function create()
    {
        return view('admin.markets.form', ['market' => new Market(['city' => 'Dar es Salaam'])]);
    }

    public function store(Request $request)
    {
        Market::create($this->data($request) + ['status' => 'approved', 'created_by' => $request->user()->id]);
        return redirect()->route('admin.markets.index')->with('ok', __('soko.flash.saved'));
    }

    public function edit(Market $market)
    {
        return view('admin.markets.form', compact('market'));
    }

    public function update(Request $request, Market $market)
    {
        $market->update($this->data($request));
        return redirect()->route('admin.markets.index')->with('ok', __('soko.flash.saved'));
    }

    public function destroy(Market $market)
    {
        if ($market->sellers()->exists() || $market->priceRecords()->exists()) {
            return back()->with('error', __('soko.flash.cannot_delete_in_use'));
        }
        $market->delete();
        return back()->with('ok', __('soko.flash.deleted'));
    }

    public function approve(Market $market)
    {
        $market->update(['status' => 'approved']);
        return back()->with('ok', __('soko.flash.saved'));
    }

    private function data(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:120',
            'city' => 'nullable|string|max:80',
            'area' => 'nullable|string|max:120',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90|required_with:longitude',
            'longitude' => 'nullable|numeric|between:-180,180|required_with:latitude',
        ]);
    }
}
