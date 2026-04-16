<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat data penjualan 30 hari terakhir untuk setiap produk.
        // Setiap produk diberi range penjualan yang berbeda untuk menampilkan variasi kecepatan jual.
        $productSales = [
            'Laptop Dell' => [1, 3],
            'Mouse Logitech' => [0, 2],
            'Keyboard Mechanical' => [0, 1],
            'Printer Canon' => [0, 1],
            'Monitor Samsung' => [0, 1],
            'Router TP-Link' => [0, 1],
            'Harddisk Seagate' => [0, 1],
            'SSD Western Digital' => [0, 1],
            'Headset HyperX' => [0, 2],
            'Projector Epson' => [0, 1],
            'Office Chair' => [0, 1],
            'Webcam Logitech' => [0, 2],
            'UPS APC' => [0, 1],
            'Flashdisk Sandisk' => [0, 3],
            'Smartphone Xiaomi' => [0, 2],
        ];

        foreach ($productSales as $productName => $range) {
            $product = \App\Models\Product::where('name', $productName)->first();
            if (! $product) {
                continue;
            }

            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i)->toDateString();
                \App\Models\Sale::create([
                    'product_id' => $product->id,
                    'quantity_sold' => rand($range[0], $range[1]),
                    'sale_date' => $date,
                ]);
            }
        }
    }
}
