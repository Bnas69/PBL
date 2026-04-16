<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReorderDraft extends Model
{
    protected $fillable = ['product_id', 'suggested_quantity', 'reason', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
