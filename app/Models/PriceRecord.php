<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PriceRecord extends Model
{
    protected $fillable = ['product_id', 'seller_id', 'market_id', 'price', 'currency', 'stock_qty', 'source', 'recorded_at'];
    protected $casts = ['recorded_at' => 'datetime'];

    public function product() { return $this->belongsTo(Product::class); }
    public function seller() { return $this->belongsTo(Seller::class); }
    public function market() { return $this->belongsTo(Market::class); }
}
