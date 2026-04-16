<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Inventory;
use App\Models\ReorderDraft;
use Carbon\Carbon;

class StockAnalysisService
{
    public function analyzeAndCreateReorderDrafts()
    {
        $products = Product::with('inventory')->get();

        foreach ($products as $product) {
            $inventory = $product->inventory;
            if (!$inventory) {
                continue;
            }

            $currentStock = $inventory->quantity;
            $minStock = $inventory->min_stock_level;

            $recentSales = Sale::where('product_id', $product->id)
                ->where('sale_date', '>=', Carbon::now()->subDays(30))
                ->sum('quantity_sold');

            $avgDailySales = $recentSales / 30;
            $daysToDepletion = $avgDailySales > 0 ? $currentStock / $avgDailySales : PHP_INT_MAX;

            $needsReorder = $daysToDepletion <= 3 && $currentStock <= $minStock;

            if (! $needsReorder) {
                continue;
            }

            $suggestedQuantity = max($minStock * 2 - $currentStock, 10);
            $reason = "Stok akan habis dalam " . round($daysToDepletion, 1) . " hari berdasarkan tren penjualan.";

            ReorderDraft::updateOrCreate([
                'product_id' => $product->id,
                'status' => 'pending',
            ], [
                'suggested_quantity' => $suggestedQuantity,
                'reason' => $reason,
            ]);
        }
    }

    public function getStockData()
    {
        return Product::with('inventory')->get()->map(function ($product) {
            $inventory = $product->inventory;
            $currentStock = $inventory ? $inventory->quantity : 0;
            $minStock = $inventory ? $inventory->min_stock_level : 0;
            $recentSales = Sale::where('product_id', $product->id)
                ->where('sale_date', '>=', Carbon::now()->subDays(30))
                ->sum('quantity_sold');

            $avgDailySales = $recentSales / 30;
            $predictedDays = $avgDailySales > 0 ? $currentStock / $avgDailySales : null;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'current_stock' => $currentStock,
                'min_stock_level' => $minStock,
                'avg_daily_sales' => round($avgDailySales, 2),
                'predicted_days_to_depletion' => $predictedDays !== null ? round($predictedDays, 1) : 'N/A',
                'needs_reorder' => $predictedDays !== null && $predictedDays <= 3 && $currentStock <= $minStock,
            ];
        });
    }

    public function getSalesTrend($productId, $days = 30)
    {
        return Sale::where('product_id', $productId)
            ->where('sale_date', '>=', Carbon::now()->subDays($days))
            ->orderBy('sale_date')
            ->get()
            ->groupBy('sale_date')
            ->map(function ($sales) {
                return $sales->sum('quantity_sold');
            });
    }
}