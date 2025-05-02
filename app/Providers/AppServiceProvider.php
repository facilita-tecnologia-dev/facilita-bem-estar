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

        Gate::define('is-internal-manager', function(User $user): Response {
            if($user->hasRole('internal-manager')){
                return Response::allow();
            }

            return Response::denyAsNotFound();
        });

        Gate::define('is-employee', function(User $user): Response {
            if($user->hasRole('employee')){
                return Response::allow();
            }

            return Response::denyAsNotFound();
        });

        Gate::define('access-psychosocial', function(User $user): Response {
            if(session('company')->id !== 2){
                return Response::allow();
            }

            return Response::denyAsNotFound();
        });

        Gate::define('access-organizational', function(User $user): Response {
            if(in_array(session('company')->id, [1,2])){
                return Response::allow();
            }

            return Response::denyAsNotFound();
        });

        View::composer('components.structure.sidebar', SidebarComposer::class);
        View::composer('components.filter-actions', FiltersComposer::class);
    }
}
