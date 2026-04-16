<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'supplier', 'price'];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function reorderDrafts()
    {
        return $this->hasMany(ReorderDraft::class);
    }
}
