<?php

namespace App\Filament\Imports\Jobs;

use Filament\Actions\Imports\Jobs\ImportCsv;
use Illuminate\Support\Facades\Cache;

class ProductImportCsv extends ImportCsv
{
    public function handle(): void
    {
        Cache::lock('product-imports', 3600)->block(3600, function (): void {
            parent::handle();
        });
    }
}
