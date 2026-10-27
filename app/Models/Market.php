<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $fillable = ['name', 'city', 'area', 'latitude', 'longitude'];
    public function sellers() { return $this->hasMany(Seller::class); }
}
