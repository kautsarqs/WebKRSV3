<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {

    }

    public function boot(): void
    {
        Paginator::useTailwind();

        if (config('app.env') === 'production' || request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email - KEBUN RAYA SAMBAS')
                ->view('emails.verify', ['url' => $url, 'user' => $notifiable]);
        });

        try {
            $manifestPath = public_path('build/manifest.json');
            $swPath = public_path('sw.js');

            if (file_exists($manifestPath) && file_exists($swPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                $buildAssets = [];

                if (is_array($manifest)) {
                    foreach ($manifest as $file) {
                        if (isset($file['file'])) {
                            $buildAssets[] = '/build/' . $file['file'];
                        }
                        if (isset($file['css'])) {
                            foreach ($file['css'] as $cssFile) {
                                $buildAssets[] = '/build/' . $cssFile;
                            }
                        }
                    }
                }

                $baseAssets = [
                    '/',
                    '/peta',
                    '/manifest.json',
                    '/storage/images/logoKRS.png',
                    '/storage/images/logoKRS_square.png',
                    '/favicon.ico',
                    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
                    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js'
                ];

                $allAssets = array_unique(array_merge($baseAssets, $buildAssets));
                sort($allAssets);

                $swContent = file_get_contents($swPath);

                $assetsJsString = "const ASSETS_TO_CACHE = [\n  " . implode(",\n  ", array_map(function($path) {
                    return "'" . str_replace("'", "\\'", $path) . "'";
                }, $allAssets)) . "\n];";

                if (strpos($swContent, $assetsJsString) === false) {

                    $newCacheVersion = 'krs-cache-' . time();

                    $updatedContent = preg_replace(
                        '/const ASSETS_TO_CACHE = \[.*?\];/s',
                        $assetsJsString,
                        $swContent
                    );

                    $updatedContent = preg_replace(
                        "/const CACHE_NAME = '.*?';/",
                        "const CACHE_NAME = '{$newCacheVersion}';",
                        $updatedContent
                    );

                    file_put_contents($swPath, $updatedContent);
                }
            }
        } catch (\Exception $e) {

            logger()->warning('Failed to auto-update PWA service worker assets: ' . $e->getMessage());
        }
    }
}
