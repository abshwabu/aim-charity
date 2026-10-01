<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;

class MediaHelper
{
    /**
     * Standard responsive image upload with editor and disk cleanup.
     */
    public static function image(string $name, string $directory, string $label = 'Image'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->image()
            ->imageEditor()
            ->maxSize(5120)
            ->imageResizeMode('cover')
            ->imageResizeTargetWidth('1600')
            ->imageResizeTargetHeight('1000')
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
            ->deleteUploadedFileUsing(function (string $file): void {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            });
    }

    /**
     * Logo or brand icon upload field.
     */
    public static function logo(string $name, string $directory, string $label = 'Logo'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->image()
            ->imageEditor()
            ->maxSize(3072)
            ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/webp', 'image/jpeg'])
            ->deleteUploadedFileUsing(function (string $file): void {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            });
    }

    /**
     * Portrait or square profile photo upload field.
     */
    public static function avatar(string $name, string $directory, string $label = 'Photo'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatioOptions(['1:1', '4:5'])
            ->maxSize(4096)
            ->imageResizeTargetWidth('800')
            ->imageResizeTargetHeight('800')
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->deleteUploadedFileUsing(function (string $file): void {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            });
    }
}
