<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Helpers\ImageOptimizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizerTest extends TestCase
{
    public function test_image_is_converted_to_avif()
    {
        Storage::fake('public');

        // Create a fake PNG image
        $file = UploadedFile::fake()->image('test.png', 100, 100);

        // Convert it to avif
        $storedPath = ImageOptimizer::convertToAvif($file, 'test_images', 'public');

        // Assert it saved as .avif extension
        $this->assertStringEndsWith('.avif', $storedPath);

        // Assert file exists in the storage disk
        Storage::disk('public')->assertExists($storedPath);

        // Assert the stored file header starts with AVIF signature (ftypavif)
        $content = Storage::disk('public')->get($storedPath);
        $this->assertStringContainsString('ftypavif', substr($content, 0, 20));
    }
}
