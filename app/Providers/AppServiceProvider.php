<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Custom email verification representation
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email - KEBUN RAYA SAMBAS')
                ->view('emails.verify', ['url' => $url, 'user' => $notifiable]);
        });
    }
}
