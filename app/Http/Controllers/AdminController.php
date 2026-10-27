<?php
namespace App\Http\Controllers;

use App\Models\PriceRecord;
use App\Models\PriceReport;

class AdminController extends Controller
{
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
        return back()->with('ok', 'Ripoti imekubaliwa.');
    }

    public function reject(PriceReport $report)
    {
        $report->update(['status' => 'rejected']);
        return back()->with('ok', 'Ripoti imekataliwa.');
    }
}
