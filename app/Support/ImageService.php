<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Interfaces\ImageManagerInterface;

class ImageService
{
    public function __construct(
        protected ImageManagerInterface $imageManager
    ) {}

    /**
     * Convert an uploaded image or existing path to WebP, resize if specified, and save to disk.
     */
    public function convertToWebp(
        UploadedFile|string $file,
        string $directory = 'images',
        ?int $maxWidth = 1920,
        ?int $maxHeight = 1080,
        int $quality = 85,
        string $disk = 'public'
    ): string {
        $source = $file instanceof UploadedFile ? $file->getRealPath() : $file;

        $image = $this->imageManager->decodePath($source);

        if ($maxWidth !== null || $maxHeight !== null) {
            $image->scaleDown(width: $maxWidth, height: $maxHeight);
        }

        $encoded = $image->encode(new WebpEncoder(quality: $quality));

        $filename = Str::uuid()->toString().'.webp';
        $path = trim($directory, '/').'/'.$filename;

        Storage::disk($disk)->put($path, $encoded->toString());

        return $path;
    }

    /**
     * Resize an image to exact or bounded dimensions.
     */
    public function resize(
        UploadedFile|string $file,
        int $width,
        ?int $height = null,
        string $directory = 'images',
        int $quality = 85,
        string $disk = 'public'
    ): string {
        $source = $file instanceof UploadedFile ? $file->getRealPath() : $file;

        $image = $this->imageManager->decodePath($source);
        $image->scale(width: $width, height: $height);

        $encoded = $image->encode(new WebpEncoder(quality: $quality));

        $filename = Str::uuid()->toString().'.webp';
        $path = trim($directory, '/').'/'.$filename;

        Storage::disk($disk)->put($path, $encoded->toString());

        return $path;
    }
}
