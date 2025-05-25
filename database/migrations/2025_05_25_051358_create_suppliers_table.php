<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi database.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // Kunci utama otomatis, auto-increment
            $table->string('name')->comment('Nama pemasok'); // Nama pemasok, wajib diisi
            $table->text('contact')->nullable()->comment('Informasi kontak (misal: telepon, email)'); // Kontak pemasok, opsional
            $table->text('address')->nullable()->comment('Alamat lengkap pemasok'); // Alamat pemasok, opsional
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Batalkan migrasi database.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};