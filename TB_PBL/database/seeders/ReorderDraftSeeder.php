<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReorderDraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh draft permintaan pembelian ulang untuk produk yang stoknya sudah menipis.
        \App\Models\ReorderDraft::create([
            'product_id' => 1,
            'suggested_quantity' => 20,
            'reason' => 'Stok laptop Dell sudah di bawah level minimum. Perlu penambahan untuk menjamin ketersediaan.',
            'status' => 'pending',
        ]);

        \App\Models\ReorderDraft::create([
            'product_id' => 2,
            'suggested_quantity' => 30,
            'reason' => 'Stok mouse Logitech berada di sekitar level minimum dan penjualan masih stabil.',
            'status' => 'approved',
        ]);

        \App\Models\ReorderDraft::create([
            'product_id' => 3,
            'suggested_quantity' => 15,
            'reason' => 'Keyboard gaming hampir mencapai batas minimum setelah penjualan beberapa hari terakhir.',
            'status' => 'rejected',
        ]);
    }
}
