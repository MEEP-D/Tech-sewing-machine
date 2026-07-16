<?php

namespace App\Filament\Support;

use Filament\Forms\Components\RichEditor;

class AdminRichEditor
{
    public static function configure(RichEditor $editor, string $attachmentsDirectory): RichEditor
    {
        return $editor
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory($attachmentsDirectory)
            ->fileAttachmentsAcceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->fileAttachmentsMaxSize(4096)
            ->resizableImages()
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike'],
                ['h2', 'h3'],
                ['alignStart', 'alignCenter', 'alignEnd'],
                ['bulletList', 'orderedList'],
                ['link', 'blockquote', 'attachFiles'],
                ['undo', 'redo'],
            ])
            ->helperText('Có thể căn trái, căn giữa, căn phải; bấm biểu tượng kẹp ảnh để tải lên hoặc paste ảnh trực tiếp vào khung soạn thảo.')
            ->columnSpanFull();
    }

}
