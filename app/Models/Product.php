<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'brand', 'model', 'specs', 'slug'];
    protected $casts = ['specs' => 'array'];

    public function category() { return $this->belongsTo(Category::class); }
    public function priceRecords() { return $this->hasMany(PriceRecord::class); }
}
