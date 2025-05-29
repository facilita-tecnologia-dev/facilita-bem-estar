<?php

namespace App\Providers;

use App\View\Composers\FiltersComposer;
use App\View\Composers\SidebarComposer;
use App\View\Composers\UserWelcomeComposer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        
        View::composer('components.structure.sidebar', SidebarComposer::class);
        View::composer('components.filter-actions', FiltersComposer::class);
        View::composer('private.welcome.user', UserWelcomeComposer::class);
    }
}
