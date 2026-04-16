<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat data produk contoh yang akan dipakai untuk simulasi stok dan penjualan.
        // Setiap produk berisi nama, deskripsi, supplier, dan harga satuan.
        $products = [
            ['name' => 'Laptop Dell', 'description' => 'Laptop untuk kantor', 'supplier' => 'PT. Dell Indonesia', 'price' => 15000000],
            ['name' => 'Mouse Logitech', 'description' => 'Mouse wireless', 'supplier' => 'PT. Logitech Asia', 'price' => 150000],
            ['name' => 'Keyboard Mechanical', 'description' => 'Keyboard gaming', 'supplier' => 'PT. Gaming Corp', 'price' => 500000],
            ['name' => 'Printer Canon', 'description' => 'Printer inkjet warna', 'supplier' => 'PT. Canon Indonesia', 'price' => 2500000],
            ['name' => 'Monitor Samsung', 'description' => 'Monitor LED 24 inch', 'supplier' => 'PT. Samsung Electronics', 'price' => 3200000],
            ['name' => 'Router TP-Link', 'description' => 'Router Wi-Fi dual-band', 'supplier' => 'PT. TP-Link Indonesia', 'price' => 450000],
            ['name' => 'Harddisk Seagate', 'description' => 'Harddisk eksternal 1TB', 'supplier' => 'PT. Seagate Technology', 'price' => 700000],
            ['name' => 'SSD Western Digital', 'description' => 'SSD 512GB NVMe', 'supplier' => 'PT. Western Digital', 'price' => 1100000],
            ['name' => 'Headset HyperX', 'description' => 'Headset gaming stereo', 'supplier' => 'PT. HyperX Indonesia', 'price' => 900000],
            ['name' => 'Projector Epson', 'description' => 'Proyektor kantor', 'supplier' => 'PT. Epson Indonesia', 'price' => 8500000],
            ['name' => 'Office Chair', 'description' => 'Kursi kantor ergonomic', 'supplier' => 'PT. Office Comfort', 'price' => 1200000],
            ['name' => 'Webcam Logitech', 'description' => 'Webcam HD untuk meeting', 'supplier' => 'PT. Logitech Asia', 'price' => 550000],
            ['name' => 'UPS APC', 'description' => 'UPS 650VA', 'supplier' => 'PT. APC Indonesia', 'price' => 1300000],
            ['name' => 'Flashdisk Sandisk', 'description' => 'Flashdisk 64GB USB 3.0', 'supplier' => 'PT. Sandisk Indonesia', 'price' => 120000],
            ['name' => 'Smartphone Xiaomi', 'description' => 'Smartphone Android 6.5 inch', 'supplier' => 'PT. Xiaomi Indonesia', 'price' => 3000000],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
