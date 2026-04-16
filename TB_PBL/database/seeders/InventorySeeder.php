<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat data inventaris untuk setiap produk.
        // quantity = jumlah stok saat ini.
        // min_stock_level = batas minimum stok agar toko tetap dapat memenuhi permintaan.
        $inventories = [
            ['name' => 'Laptop Dell', 'quantity' => 5, 'min_stock_level' => 10],
            ['name' => 'Mouse Logitech', 'quantity' => 50, 'min_stock_level' => 20],
            ['name' => 'Keyboard Mechanical', 'quantity' => 15, 'min_stock_level' => 10],
            ['name' => 'Printer Canon', 'quantity' => 8, 'min_stock_level' => 10],
            ['name' => 'Monitor Samsung', 'quantity' => 12, 'min_stock_level' => 8],
            ['name' => 'Router TP-Link', 'quantity' => 25, 'min_stock_level' => 15],
            ['name' => 'Harddisk Seagate', 'quantity' => 18, 'min_stock_level' => 12],
            ['name' => 'SSD Western Digital', 'quantity' => 10, 'min_stock_level' => 8],
            ['name' => 'Headset HyperX', 'quantity' => 22, 'min_stock_level' => 15],
            ['name' => 'Projector Epson', 'quantity' => 3, 'min_stock_level' => 5],
            ['name' => 'Office Chair', 'quantity' => 20, 'min_stock_level' => 10],
            ['name' => 'Webcam Logitech', 'quantity' => 14, 'min_stock_level' => 10],
            ['name' => 'UPS APC', 'quantity' => 9, 'min_stock_level' => 8],
            ['name' => 'Flashdisk Sandisk', 'quantity' => 60, 'min_stock_level' => 25],
            ['name' => 'Smartphone Xiaomi', 'quantity' => 7, 'min_stock_level' => 6],
        ];

        foreach ($inventories as $inventoryData) {
            $product = \App\Models\Product::where('name', $inventoryData['name'])->first();
            if (! $product) {
                continue;
            }

            \App\Models\Inventory::create([
                'product_id' => $product->id,
                'quantity' => $inventoryData['quantity'],
                'min_stock_level' => $inventoryData['min_stock_level'],
            ]);
        }
    }
}
