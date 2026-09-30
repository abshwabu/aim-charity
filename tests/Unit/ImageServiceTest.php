<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Interfaces\ImageManagerInterface;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    public function test_it_converts_image_to_webp_and_resizes(): void
    {
        Storage::fake('public');

        /** @var ImageManagerInterface $imageManager */
        $imageManager = app('intervention.image');
        $service = new ImageService($imageManager);

        $file = UploadedFile::fake()->image('test.png', 800, 600);

        $path = $service->convertToWebp($file, directory: 'test-images', maxWidth: 400, maxHeight: 300);

        Storage::disk('public')->assertExists($path);
        $this->assertStringEndsWith('.webp', $path);

        $contents = Storage::disk('public')->get($path);
        $this->assertNotNull($contents);

        $decoded = $imageManager->decodeBinary($contents);

        $this->assertLessThanOrEqual(400, $decoded->width());
        $this->assertLessThanOrEqual(300, $decoded->height());
    }
}
