<?php
namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\PriceRecord;
use App\Models\PriceReport;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function overview()
    {
        return view('admin.overview', [
            'counts' => [
                'users' => User::count(),
                'shops' => Seller::count(),
                'markets' => Market::approved()->count(),
                'products' => Product::where('status', 'approved')->count(),
                'records' => PriceRecord::count(),
            ],
            'pending' => [
                'reports' => PriceReport::where('status', 'pending')->count(),
                'markets' => Market::where('status', 'pending')->count(),
                'products' => Product::where('status', 'pending')->count(),
                'shops' => Seller::where('is_verified', false)->count(),
            ],
        ]);
    }

    /* ---------- Ripoti za bei ---------- */

    public function reports()
    {
        return view('admin.reports', ['reports' => PriceReport::with(['user', 'product', 'market'])->where('status', 'pending')->latest()->get()]);
    }

    public function approve(PriceReport $report)
    {
        abort_unless($report->status === 'pending', 409);
        PriceRecord::create([
            'product_id' => $report->product_id, 'market_id' => $report->market_id,
            'price' => $report->price, 'source' => 'user_report', 'recorded_at' => now(),
        ]);
        $report->update(['status' => 'approved']);
        return back()->with('ok', __('soko.flash.report_approved'));
    }

    public function reject(PriceReport $report)
    {
        $report->update(['status' => 'rejected']);
        return back()->with('ok', __('soko.flash.report_rejected'));
    }

    /* ---------- Biashara (maduka) ---------- */

    public function shops()
    {
        return view('admin.shops', ['shops' => Seller::with(['user', 'market'])->orderBy('is_verified')->latest()->paginate(25)]);
    }

    public function toggleVerify(Seller $shop)
    {
        $shop->update(['is_verified' => ! $shop->is_verified]);
        return back()->with('ok', __('soko.flash.saved'));
    }

    public function toggleShopStatus(Seller $shop)
    {
        $shop->update(['status' => $shop->status === 'active' ? 'suspended' : 'active']);
        return back()->with('ok', __('soko.flash.saved'));
    }

    /* ---------- Watumiaji ---------- */

    public function users()
    {
        return view('admin.users', ['users' => User::orderByDesc('created_at')->paginate(25)]);
    }

    public function updateRole(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', __('soko.flash.not_yourself'));
        }
        $data = $request->validate(['role' => 'required|in:admin,seller,buyer']);
        $user->update($data);
        return back()->with('ok', __('soko.flash.saved'));
    }

    public function toggleActive(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', __('soko.flash.not_yourself'));
        }
        $user->update(['is_active' => ! $user->is_active]);
        return back()->with('ok', __('soko.flash.saved'));
    }

    /* ---------- Rekodi za bei ---------- */

    public function records()
    {
        return view('admin.records', ['records' => PriceRecord::with(['product', 'market', 'seller'])->latest('recorded_at')->paginate(30)]);
    }

    public function destroyRecord(PriceRecord $record)
    {
        $record->delete();
        return back()->with('ok', __('soko.flash.deleted'));
    }

    /** CSV ya rekodi zote za bei: ya kuchambua kwa Excel au Python baadaye. */
    public function exportPrices()
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['id', 'product', 'market', 'shop', 'price', 'currency', 'stock_qty', 'source', 'recorded_at']);
            PriceRecord::with(['product:id,name', 'market:id,name', 'seller:id,business_name'])
                ->orderBy('id')->chunkById(1000, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        fputcsv($out, [$r->id, $r->product?->name, $r->market?->name, $r->seller?->business_name,
                            $r->price, $r->currency, $r->stock_qty, $r->source, $r->recorded_at?->toDateTimeString()]);
                    }
                });
            fclose($out);
        }, 'price_records_'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
