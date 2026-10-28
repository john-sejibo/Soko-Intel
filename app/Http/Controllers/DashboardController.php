<?php
namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\PriceRecord;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        // Wastani wa bei: siku 7 za mwisho dhidi ya siku 7 zilizotangulia
        $cur = PriceRecord::where('recorded_at', '>=', $now->copy()->subDays(7))
            ->selectRaw('product_id, AVG(price) as a')->groupBy('product_id')->pluck('a', 'product_id');
        $prev = PriceRecord::whereBetween('recorded_at', [$now->copy()->subDays(14), $now->copy()->subDays(7)])
            ->selectRaw('product_id, AVG(price) as a')->groupBy('product_id')->pluck('a', 'product_id');
        $daily = PriceRecord::where('recorded_at', '>=', $now->copy()->subDays(14))
            ->selectRaw('product_id, DATE(recorded_at) as d, AVG(price) as a')
            ->groupBy('product_id', 'd')->orderBy('d')->get()->groupBy('product_id');

        $movers = Product::where('status', 'approved')->get()->map(function ($p) use ($cur, $prev, $daily) {
            $c = $cur[$p->id] ?? null;
            $pv = $prev[$p->id] ?? null;
            return (object) [
                'product' => $p,
                'price'   => $c ? (int) round($c) : null,
                'change'  => ($c && $pv) ? round((($c - $pv) / $pv) * 100, 1) : null,
                'spark'   => $this->spark($daily[$p->id] ?? collect()),
            ];
        })->filter(fn ($m) => $m->price)
          ->sortByDesc(fn ($m) => abs($m->change ?? 0))->values();

        // Kiwango cha bei cha kila soko: 100 = wastani wa masoko yote (chini = nafuu)
        $pm = PriceRecord::where('recorded_at', '>=', $now->copy()->subDays(14))
            ->selectRaw('product_id, market_id, AVG(price) as a')->groupBy('product_id', 'market_id')->get();
        $prodAvg = $pm->groupBy('product_id')->map(fn ($g) => $g->avg('a'));
        $marketNames = Market::pluck('name', 'id');
        $levels = $pm->groupBy('market_id')
            ->map(fn ($g) => round($g->avg(fn ($r) => $r->a / $prodAvg[$r->product_id] * 100), 1))
            ->map(fn ($idx, $id) => (object) ['name' => $marketNames[$id] ?? 'Soko', 'index' => $idx])
            ->sortBy('index')->values();

        return view('dashboard', [
            'movers' => $movers,
            'levels' => $levels,
            'recent' => PriceRecord::with(['product', 'market', 'seller'])->latest('recorded_at')->limit(8)->get(),
            'stats'  => [
                'products' => Product::where('status', 'approved')->count(),
                'markets'  => Market::approved()->count(),
                'sellers'  => Seller::where('is_verified', true)->count(),
                'today'    => PriceRecord::where('recorded_at', '>=', $now->copy()->subDay())->count(),
            ],
        ]);
    }

    /** Wastani wa kila siku -> pointi za SVG polyline (100 x 28). */
    private function spark(Collection $days): string
    {
        $vals = $days->pluck('a')->map(fn ($v) => (float) $v)->values();
        $n = $vals->count();
        if ($n < 2) return '';
        $min = $vals->min();
        $span = max($vals->max() - $min, 1);
        return $vals->map(fn ($v, $i) => round($i / ($n - 1) * 100, 1).','.round(26 - (($v - $min) / $span) * 24, 1))->implode(' ');
    }
}
