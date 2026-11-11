<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

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
        Gate::define('viewPulse', function (User $user) {
            return ($user->role === 'admin') ? true : false;
        });

        View::composer('*', function ($view) {

            $onlineUserIds = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', Carbon::now()->subMinutes(5)->timestamp)
                ->pluck('user_id')
                ->map(fn($id) => (int) $id) // ensure integers
                ->toArray();

            $view->with('onlineUserIds', $onlineUserIds);
        });

        // URL::forceScheme('https'); // uncomment this if ur using ngrok
    }
}
