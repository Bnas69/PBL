<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['product_id', 'quantity', 'min_stock_level'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
