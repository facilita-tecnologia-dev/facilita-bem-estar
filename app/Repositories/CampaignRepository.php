<?php

namespace App\Repositories;

use App\Enums\InternalUserRoleEnum;
use App\Imports\UsersImport;
use App\Models\Company;
use App\Models\CompanyCampaign;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ValidatedInput;

class CampaignRepository
{
    public function store(FormRequest $request) : CompanyCampaign
    {
        return DB::transaction(function() use($request) {
            $validatedData = $request->validated();

            $campaign = CompanyCampaign::create([
                'company_id' => session('company')->id,
                'collection_id' => $validatedData['collection_id'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date']
            ]);

            return $campaign;
        });
    }

    public function update(CompanyCampaign $campaign, FormRequest $request) : CompanyCampaign
    {
        return DB::transaction(function () use ($campaign, $request) {
            $campaign->update($request->safe()->toArray());
            
            return $campaign;
        });
    }

    public function destroy(CompanyCampaign $campaign) : mixed
    {
        return $campaign->delete();
    }
}
