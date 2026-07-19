<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizer
{

    public static function convertToAvif(UploadedFile $file, string $directory, string $disk = 'public', int $quality = 80): string
    {
        $mime = $file->getMimeType();

        if ($mime === 'image/avif') {
            return $file->store($directory, $disk);
        }

        if ($mime === 'image/svg+xml' || $file->getClientOriginalExtension() === 'svg') {
            return $file->store($directory, $disk);
        }

        if (!extension_loaded('gd')) {
            return $file->store($directory, $disk);
        }

        @ini_set('memory_limit', '512M');

        $tempPath = $file->getRealPath();
        if (!$tempPath || !file_exists($tempPath)) {
            return $file->store($directory, $disk);
        }

        $image = null;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/pjpeg':
                $image = @imagecreatefromjpeg($tempPath);

                if ($image && function_exists('exif_read_data')) {
                    $exif = @exif_read_data($tempPath);
                    if (!empty($exif['Orientation'])) {
                        $angle = 0;
                        switch ($exif['Orientation']) {
                            case 3:
                                $angle = 180;
                                break;
                            case 6:
                                $angle = -90;
                                break;
                            case 8:
                                $angle = 90;
                                break;
                        }
                        if ($angle !== 0) {
                            $rotated = @imagerotate($image, $angle, 0);
                            if ($rotated !== false) {
                                imagedestroy($image);
                                $image = $rotated;
                            }
                        }
                    }
                }
                break;
            case 'image/png':
            case 'image/x-png':
                $image = @imagecreatefrompng($tempPath);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($tempPath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($tempPath);
                break;
            case 'image/bmp':
            case 'image/x-ms-bmp':
                if (function_exists('imagecreatefrombmp')) {
                    $image = @imagecreatefrombmp($tempPath);
                }
                break;
        }

        if (!$image) {
            return $file->store($directory, $disk);
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        if (function_exists('imageavif')) {
            $tempAvif = tempnam(sys_get_temp_dir(), 'avif_');
            $success = @imageavif($image, $tempAvif, $quality);

            if ($success) {
                imagedestroy($image);
                $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.avif';
                $storedPath = Storage::disk($disk)->putFileAs($directory, new \Illuminate\Http\File($tempAvif), $filename);
                @unlink($tempAvif);
                return $storedPath;
            }
            @unlink($tempAvif);
        }

        if (function_exists('imagewebp')) {
            $tempWebp = tempnam(sys_get_temp_dir(), 'webp_');
            $success = @imagewebp($image, $tempWebp, $quality);

            if ($success) {
                imagedestroy($image);
                $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
                $storedPath = Storage::disk($disk)->putFileAs($directory, new \Illuminate\Http\File($tempWebp), $filename);
                @unlink($tempWebp);
                return $storedPath;
            }
            @unlink($tempWebp);
        }

        imagedestroy($image);
        return $file->store($directory, $disk);
    }
}
