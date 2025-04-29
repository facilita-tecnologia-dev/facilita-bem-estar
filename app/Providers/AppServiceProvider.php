<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\UserPolicy;
use App\View\Composers\FiltersComposer;
use App\View\Composers\SidebarComposer;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Company::class, CompanyPolicy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Gate::define('view-manager-screens', function (User $user): Response {
            if ($user->hasRole('internal-manager')) {
                return Response::allow();
            }

            return Response::denyAsNotFound();
        });

        Gate::define('answer-tests', function (User $user): Response {
            if ($user->hasRole('internal-manager') || $user->hasRole('employee')) {
                return Response::allow();
            }

            return Response::denyAsNotFound();
        });

        Gate::define('update-metrics', function (User $user): Response {
            if ($user->hasRole('internal-manager')) {
                return Response::allow();
            }

            return Response::denyAsNotFound();
        });

        // Gate::define('answer-test', function (User $user) {
        //     return !$user->collections->count();
        // });

        View::composer('components.structure.sidebar', SidebarComposer::class);
        View::composer('components.filters-trigger', FiltersComposer::class);
    }
}
