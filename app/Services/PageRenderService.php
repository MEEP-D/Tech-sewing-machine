<?php

namespace App\Services;

use App\Models\Page;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Support\Arr;

class PageRenderService
{
    public function renderedHtml(Page $page, bool $isBuilderMode = false): string
    {
        $html = $this->renderContent((string) ($page->content ?? ''));

        if (! $isBuilderMode) {
            return $html;
        }

        $config = is_array($page->style_config) ? $page->style_config : [];
        $maxWidth = $this->safeCssValue(Arr::get($config, 'max_width'));
        $padding = $this->safeCssValue(Arr::get($config, 'padding'));
        $background = $this->safeCssValue(Arr::get($config, 'background'));
        $textColor = $this->safeCssValue(Arr::get($config, 'color'));

        $styles = [];
        if ($maxWidth) {
            $styles[] = "max-width: {$maxWidth}";
        }
        if ($padding) {
            $styles[] = "padding: {$padding}";
        }
        if ($background) {
            $styles[] = "background: {$background}";
        }
        if ($textColor) {
            $styles[] = "color: {$textColor}";
        }

        $styleAttribute = empty($styles) ? '' : ' style="' . e(implode('; ', $styles)) . '"';

        return '<div class="page-builder-layout"' . $styleAttribute . '>' . $html . '</div>';
    }

    private function renderContent(string $content): string
    {
        $trimmed = trim($content);

        if ($trimmed === '') {
            return '';
        }

        $decoded = null;
        if (($trimmed[0] ?? '') === '{' || ($trimmed[0] ?? '') === '[') {
            $decoded = json_decode($trimmed, true);
        }

        if (is_array($decoded) && class_exists(RichContentRenderer::class)) {
            return RichContentRenderer::make($decoded)->toHtml();
        }

        return $content;
    }

    private function safeCssValue(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (! preg_match('/^[#(),.%\-:;\/\s\w]+$/u', $value)) {
            return null;
        }

        return $value;
    }
}
