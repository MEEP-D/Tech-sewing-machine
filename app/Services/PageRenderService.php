<?php

namespace App\Services;

use App\Models\Page;

class PageRenderService
{
    public function renderedHtml(Page $page): string
    {
        return (string) ($page->content ?? '');
    }
}
