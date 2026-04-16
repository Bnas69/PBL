<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StockAnalysisService;
use App\Models\ReorderDraft;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(StockAnalysisService $service)
    {
        $stockData = $service->getStockData();
        $reorderDrafts = ReorderDraft::with('product')->where('status', 'pending')->get();

        return inertia('Dashboard/Index', [
            'stockData' => $stockData,
            'reorderDrafts' => $reorderDrafts,
        ]);
    }

    public function runAnalysis(StockAnalysisService $service): JsonResponse
    {
        $service->analyzeAndCreateReorderDrafts();

        return response()->json([
            'success' => true,
            'message' => 'Analisis stok dijalankan. Draft reorder telah diperbarui.'
        ]);
    }
}
