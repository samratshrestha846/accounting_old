<?php

namespace App\Providers;

use App\Models\QuotationFollowup;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        if (!$this->app->runningInConsole()) {
            $followups = QuotationFollowup::where('followup_date', '>', date("Y-m-d"))->where('followup_date', '<', date("Y-m-d", strtotime("tomorrow")))->where('is_followed', 0)->get();
            $followupscount = count($followups);
            View::share(compact('followups', 'followupscount'));
            $setting = Setting::first();

            View::composer('*', function ($view) use ($setting) {
                $view->with('setting', $setting);
                $view->with('session_time', env('SESSION_LIFETIME', 30));
            });
        }
    }
}
