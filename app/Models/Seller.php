<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $fillable = ['user_id', 'market_id', 'business_name', 'phone', 'is_verified'];
    protected $casts = ['is_verified' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function market() { return $this->belongsTo(Market::class); }
    public function priceRecords() { return $this->hasMany(PriceRecord::class); }
}
