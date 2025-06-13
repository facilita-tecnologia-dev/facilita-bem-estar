<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'companies';

    protected $fillable = ['name', 'cnpj', 'logo'];

    /* --- Relations --- */

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_users')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_users')
        ->withPivot('company_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(UserFeedback::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(CompanyMetric::class, 'company_id');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(CompanyCampaign::class, 'company_id');
    }

    public function actionPlan(): HasOne
    {
        return $this->hasOne(ActionPlan::class);
    }
    
    public function customCollections(): HasMany
    {
        return $this->hasMany(CustomCollection::class);
    }

    /* --- End Relations --- */

    public function getActiveCampaigns() : Collection
    {
        return $this->campaigns
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function hasCompletedBasicData() : bool
    {
        return $this->users->count() && $this->logo && $this->metrics()->where('value')->count();
    }

    public function hasCampaignThisYear($collectionId, $finalized = false) : bool
    {
        return $this
            ->campaigns()
            ->whereYear('start_date', now()->year)
            ->when($finalized, function($q){
                $q->where('end_date', '<' , now());
            })
            ->whereHas('collection', function($query) use($collectionId) {
                $query->where('collection_id', $collectionId);
            })
            ->exists();
    }

    public function getFirstName(): string
    {
        $name = explode(' ', $this->name);

        return $name[0];
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token, 'company'));
    }
}
