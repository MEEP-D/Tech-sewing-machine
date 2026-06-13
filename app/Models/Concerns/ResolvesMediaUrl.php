<?php

namespace App\Models\Concerns;

use App\Support\OptimizedMedia;

trait ResolvesMediaUrl
{
    protected function resolveMediaUrl(?string $path): ?string
    {
        return app(OptimizedMedia::class)->url($path);
    }
}
