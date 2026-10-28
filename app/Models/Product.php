<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'brand', 'model', 'specs', 'slug', 'status', 'created_by'];
    protected $casts = ['specs' => 'array'];

    public function category() { return $this->belongsTo(Category::class); }
    public function priceRecords() { return $this->hasMany(PriceRecord::class); }

    public static function makeSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'bidhaa';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
