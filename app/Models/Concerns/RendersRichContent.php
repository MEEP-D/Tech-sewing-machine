<?php

namespace App\Models\Concerns;

use Filament\Forms\Components\RichEditor\RichContentRenderer;

trait RendersRichContent
{
    protected function renderRichContent(?string $content): string
    {
        $content = (string) ($content ?? '');
        $trimmed = trim($content);

        if ($trimmed === '') {
            return '';
        }

        $decoded = null;
        if (($trimmed[0] ?? '') === '{' || ($trimmed[0] ?? '') === '[') {
            $decoded = json_decode($trimmed, true);
        }

        if (is_array($decoded) && class_exists(RichContentRenderer::class)) {
            $content = RichContentRenderer::make($decoded)->toHtml();
        } elseif (! $this->containsHtml($content)) {
            $content = '<p>' . nl2br(e($trimmed), false) . '</p>';
        }

        return $this->resolveRichContentImages($content);
    }

    private function containsHtml(string $content): bool
    {
        return preg_match('/<\s*[a-z][^>]*>/i', $content) === 1;
    }

    private function resolveRichContentImages(string $content): string
    {
        if ($content === '' || ! method_exists($this, 'resolveMediaUrl')) {
            return $content;
        }

        return (string) preg_replace_callback(
            '/(<img[^>]*\ssrc=["\'])([^"\']+)(["\'][^>]*>)/i',
            fn (array $matches): string => $matches[1] . ($this->resolveMediaUrl($matches[2]) ?? $matches[2]) . $matches[3],
            $content
        );
    }
}
