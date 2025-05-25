<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Product extends Model
{
    protected $fillable = ['name', 'description', 'supplier', 'supplier_contact', 'price', 'stock', 'image', 'supplier_id'];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntri::class);
    }

    public function stockExits()
    {
        return $this->hasMany(StockExit::class);
    }

    public function getStockAttribute()
    {
        $totalMasuk = $this->stockEntries()->sum('quantity');
        $totalKeluar = $this->stockExits()->sum('quantity');
        return $totalMasuk - $totalKeluar;
    }

}
