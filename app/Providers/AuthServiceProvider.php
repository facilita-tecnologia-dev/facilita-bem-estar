<?php

namespace App\Providers;

use App\Helpers\AuthGuardHelper;
use App\Models\Company;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Repositories\TestRepository;
use App\Services\User\UserElegibilityService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    // protected $policies = [
    //     User::class => UserPolicy::class,
    // ];

    public function register(): void {}

    public function boot(): void
    {
        // $this->registerPolicies();

        Gate::define('user-index', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('user_index');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('user-show', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('user_show') || $user->id === AuthGuardHelper::user()->id;
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('user-create', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('user_create');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('user-edit', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('user_edit');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('user-delete', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('user_delete');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('answer-psychosocial-test', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                $eligibilityService = app(UserElegibilityService::class);

                return $user->hasPermission('answer_tests') && $eligibilityService->hasActivePsychosocialCampaign();
            }

            return false;
        });

        Gate::define('answer-organizational-test', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                $eligibilityService = app(UserElegibilityService::class);

                $companyCustomTests = TestRepository::companyCustomTests();
                $defaultTests = TestRepository::defaultTests();

                return $user->hasPermission('answer_tests') && $eligibilityService->hasActiveOrganizationalCampaign() && !$user->latestOrganizationalClimateCollection;
            }

            return false;
        });

        Gate::define('feedbacks-index', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('feedbacks_index');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('company-show', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('company_show');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('company-edit', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('company_edit');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('metrics-edit', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('metrics_edit');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('psychosocial-dashboard-view', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('psychosocial_dashboard_view');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('organizational-dashboard-view', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('organizational_dashboard_view');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('demographics-dashboard-view', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('demographics_dashboard_view');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('campaign-index', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('campaign_index');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('campaign-show', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('campaign_show');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('campaign-create', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('campaign_create');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('campaign-edit', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('campaign_edit');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('campaign-delete', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('campaign_delete');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('user-permission-edit', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('user_permission_edit');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('user-department-scope-edit', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('user_department_scope_edit');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });
        
        Gate::define('collections-index', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('collections_index');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('collections-edit', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('collections_edit');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('documentation-show', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('documentation_show');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('collections-index', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('collections_index');
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return true;
            }

            return false;
        });

        Gate::define('switch-companies', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();
                
                return $user->companies->count() > 1;
            }

            return false;
        });

        Gate::define('action-plan-edit', function (?Authenticatable $user) {
            if (AuthGuardHelper::user() instanceof User) {
                /** @var User $user */
                $user = AuthGuardHelper::user();

                return $user->hasPermission('action_plan_edit') && session('company')->hasCampaignThisYear(1, true);
            }

            if (AuthGuardHelper::user() instanceof Company) {
                return session('company')->hasCampaignThisYear(1, true);
            }

            return false;
        });
    }
}
