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
        }

        if (! $this->containsHtml($content)) {
            $content = $this->formatPlainTextAsHtml($content);
        }

        return $this->resolveRichContentImages($content);
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

    private function containsHtml(string $content): bool
    {
        return strip_tags($content) !== $content;
    }

    private function formatPlainTextAsHtml(string $content): string
    {
        $content = trim(str_replace(["\r\n", "\r"], "\n", $content));

        if ($content === '') {
            return '';
        }

        $paragraphs = preg_split("/\n{2,}/", $content) ?: [];

        return implode('', array_map(
            fn (string $paragraph): string => '<p>' . nl2br(e(trim($paragraph)), false) . '</p>',
            array_values(array_filter($paragraphs, fn (string $paragraph): bool => trim($paragraph) !== ''))
        ));
    }
}
