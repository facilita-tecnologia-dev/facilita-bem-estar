<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\AdmissionRangeEnum;
use App\Enums\AgeRangeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'age', 'cpf', 'department', 'occupation', 'admission', 'gender'];

    /**
     * Get the companies related to this user
     *
     * @return BelongsToMany relationship with the companies
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(
            Company::class,
            'company_users',
            'user_id',
            'company_id'
        )
            ->withPivot('role_id')
            ->withTimestamps();
    }

    /**
     * Get the roles related to this user
     *
     * @return BelongsToMany relationship with the roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_users')
            ->withPivot('company_id');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(UserFeedback::class);
    }

    /**
     * Returns all test collections related to this user.
     */
    public function collections(): HasMany
    {
        return $this->hasMany(UserCollection::class);
    }

    /**
     * Returns the most recent test collection related to this user.
     *
     * @return HasOne
     */
    public function latestCollections()
    {
        return $this->hasMany(UserCollection::class)
            ->whereIn('collection_id', [1, 2])
            ->limit(2)
            ->orderBy('created_at', 'desc');
    }

    public function latestPsychosocialCollection()
    {
        return $this->hasOne(UserCollection::class)
            ->where('collection_id', 1)
            ->latest();
    }

    public function latestOrganizationalClimateCollection()
    {
        return $this->hasOne(UserCollection::class)
            ->where('collection_id', 2)
            ->latest();
    }

    public function hasAnsweredPsychosocialCollection(): bool
    {
        if ($this->latestPsychosocialCollection) {
            return $this->latestPsychosocialCollection->created_at->year == Carbon::now()->year;
        }

        return false;
    }

    public function hasAnsweredOrganizationalCollection(): bool
    {
        if ($this->latestOrganizationalClimateCollection) {
            return $this->latestOrganizationalClimateCollection->created_at->year == Carbon::now()->year;
        }

        return false;
    }

    public function scopeWithLatestOrganizationalClimateCollection($query, $only = null, $year = null)
    {
        $query->with([
            'latestOrganizationalClimateCollection' => function ($q) use ($only, $year) {
                $q->whereBetween('created_at', [Carbon::create($year, 1, 1)->startOfDay(), Carbon::create($year, 12, 31)->endOfDay()])->withCollectionTypeName('organizational-climate')->withTests($only);
            },
        ]);
    }

    public function scopeWithLatestPsychosocialCollection($query, $only = null, $year = null)
    {
        $query->with([
            'latestPsychosocialCollection' => function ($q) use ($only, $year) {
                $q->whereBetween('created_at', [Carbon::create($year, 1, 1)->startOfDay(), Carbon::create($year, 12, 31)->endOfDay()])->withCollectionTypeName('psychosocial-risks')->withTests($only);
            },
        ]);
    }

    public function scopeHasAttribute($query, $attribute, $operator, $value)
    {
        $query->when($value, function ($query) use ($attribute, $operator, $value) {
            $query->where($attribute, $operator, $value);
        });
    }

    public function scopeHasAgeRange($query, $ageRange)
    {
        if ($ageRange) {
            $minAge = 0;
            $maxAge = 0;

            switch ($ageRange) {
                case AgeRangeEnum::YOUNG->name:
                    $minAge = 18;
                    $maxAge = 25;
                    break;
                case AgeRangeEnum::EARLY_PROFESSIONAL->name:
                    $minAge = 26;
                    $maxAge = 35;
                    break;
                case AgeRangeEnum::EXPERIENCIED->name:
                    $minAge = 36;
                    $maxAge = 45;
                    break;
                case AgeRangeEnum::SENIOR->name:
                    $minAge = 46;
                    $maxAge = 100;
                    break;
            }

            $query->whereDate('birth_date', '<=', Carbon::now()->subYears($minAge))
                ->whereDate('birth_date', '>=', Carbon::now()->subYears($maxAge + 1)->addDay());
        }
    }

    public function scopeHasAdmissionRange($query, $admissionRange = null)
    {
        if ($admissionRange) {
            $minAdmission = 0;
            $maxAdmission = 0;

            switch ($admissionRange) {
                case AdmissionRangeEnum::NEW_EMPLOYEE->name:
                    $minAdmission = 0;
                    $maxAdmission = 1;
                    break;
                case AdmissionRangeEnum::EARLY_EMPLOYEE->name:
                    $minAdmission = 2;
                    $maxAdmission = 4;
                    break;
                case AdmissionRangeEnum::ESTABLISHED_EMPLOYEE->name:
                    $minAdmission = 5;
                    $maxAdmission = 10;
                    break;
                case AdmissionRangeEnum::VETERAN_EMPLOYEE->name:
                    $minAdmission = 11;
                    $maxAdmission = 100;
                    break;
            }

            $query->whereDate('admission', '<=', Carbon::now()->subYears($minAdmission))
                ->whereDate('admission', '>=', Carbon::now()->subYears($maxAdmission + 1)->addDay());
        }
    }
}
