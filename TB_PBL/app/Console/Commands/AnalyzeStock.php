<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:analyze-stock')]
#[Description('Command description')]
class AnalyzeStock extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(\App\Services\StockAnalysisService::class);
        $service->analyzeAndCreateReorderDrafts();

        $this->info('Stock analysis completed and reorder drafts created if necessary.');
    }
}
