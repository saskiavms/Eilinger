<?php

namespace App\Providers;

use App\Models\Foundation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class FoundationServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('foundation', Foundation::first());
        });
    }
}
