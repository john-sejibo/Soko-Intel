<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $fillable = ['name', 'city', 'area', 'address', 'description', 'latitude', 'longitude', 'status', 'created_by'];
    protected $casts = ['latitude' => 'float', 'longitude' => 'float'];

    public function sellers() { return $this->hasMany(Seller::class); }
    public function priceRecords() { return $this->hasMany(PriceRecord::class); }

    public function scopeApproved(Builder $q): Builder { return $q->where('status', 'approved'); }
    /** Masoko yaliyokubaliwa + yale aliyoyapendekeza mtumiaji huyu. */
    public function scopeVisibleTo(Builder $q, int $userId): Builder
    {
        return $q->where(fn ($w) => $w->where('status', 'approved')->orWhere('created_by', $userId));
    }

    public function gmapsUrl(): ?string
    {
        return ($this->latitude !== null && $this->longitude !== null)
            ? 'https://www.google.com/maps/dir/?api=1&destination='.$this->latitude.','.$this->longitude : null;
    }
}
