<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $fillable = [
        'user_id', 'market_id', 'business_name', 'phone', 'whatsapp', 'address',
        'latitude', 'longitude', 'description', 'opening_hours', 'is_verified', 'status',
    ];
    protected $casts = ['is_verified' => 'boolean', 'latitude' => 'float', 'longitude' => 'float'];

    public function user() { return $this->belongsTo(User::class); }
    public function market() { return $this->belongsTo(Market::class); }
    public function priceRecords() { return $this->hasMany(PriceRecord::class); }

    public function gmapsUrl(): ?string
    {
        return ($this->latitude !== null && $this->longitude !== null)
            ? 'https://www.google.com/maps/dir/?api=1&destination='.$this->latitude.','.$this->longitude : null;
    }
}
