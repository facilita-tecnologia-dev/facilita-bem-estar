<?php

namespace App\Repositories;

use App\Models\CompanyCampaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CampaignRepository
{
    public function store(FormRequest $request): CompanyCampaign
    {
        return DB::transaction(function () use ($request) {
            $validatedData = $request->validated();
   
            $campaign = CompanyCampaign::create([
                'company_id' => session('company')->id,
                'collection_id' => $validatedData['collection_id'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
            ]);

            session(['company' => session('company')->load('campaigns')]);

            return $campaign;
        });
    }

    public function update(CompanyCampaign $campaign, FormRequest $request): CompanyCampaign
    {
        return DB::transaction(function () use ($campaign, $request) {
            $campaign->update($request->safe()->toArray());

            
            session(['company' => session('company')->load('campaigns')]);
            
            return $campaign;
        });
    }

    public function destroy(CompanyCampaign $campaign): mixed
    {
        return $campaign->delete();
    }
}
