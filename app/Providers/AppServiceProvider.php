<?php

namespace App\Providers;

use App\Models\Visit;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            $notifVisits = Visit::with('pasien')
                ->whereIn('status_order', ['Sampling', 'Proses'])
                ->latest()
                ->take(10) // batasi jumlah notifikasi
                ->get();

            $view->with('notifVisits', $notifVisits);
        });
    }
}
