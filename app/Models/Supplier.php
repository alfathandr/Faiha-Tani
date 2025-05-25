<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini

class Supplier extends Model
{
    protected $table = 'suppliers'; // Perbaiki nama tabel dari 'supplier' menjadi 'suppliers' (sesuai migrasi)
    protected $fillable = ['name', 'contact', 'address'];

    /**
     * Get the products for the supplier.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}