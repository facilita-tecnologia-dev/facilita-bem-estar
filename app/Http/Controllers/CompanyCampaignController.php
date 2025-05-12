<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignStoreRequest;
use App\Http\Requests\CampaignUpdateRequest;
use App\Models\Collection;
use App\Models\Company;
use App\Models\CompanyCampaign;
use App\Repositories\CampaignRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CompanyCampaignController
{
    protected $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyCampaigns = Company::firstWhere('id', session('company')->id)->campaigns()->with('collection')->paginate(15);

        return view('private.campaign.index', compact('companyCampaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $collectionsToSelect = Collection::all()->map(fn($collection) => [
            'option' => $collection->name,
            'value' => $collection->id
        ]);

        return view('private.campaign.create', compact('collectionsToSelect'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignStoreRequest $request)
    {
        $companyHasSameCampaignThisYear = Company::firstWhere('id', session('company')->id)
            ->campaigns()
            ->whereYear('start_date', now()->year)
            ->where('collection_id', $request->validated('collection_id'))
            ->first();
        
        if($companyHasSameCampaignThisYear->count()){
            return back()->with('message', "Sua empresa já cadastrou uma campanha de testes de ". $companyHasSameCampaignThisYear->collection->name ." em 2025.");
        }

        $this->campaignRepository->store($request);

        return to_route('campaign.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyCampaign $campaign)
    {
        return view('private.campaign.show', [
            'campaign' => $campaign
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyCampaign $campaign)
    {
        $collectionsToSelect = Collection::all()->map(fn($collection) => [
            'option' => $collection->name,
            'value' => $collection->id
        ]);

        return view('private.campaign.edit', compact('campaign', 'collectionsToSelect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CampaignUpdateRequest $request, CompanyCampaign $campaign)
    {

        $companyHasSameCampaignThisYear = Company::firstWhere('id', session('company')->id)
            ->campaigns()
            ->whereYear('start_date', now()->year)
            ->where('collection_id', $request->validated('collection_id'))
            ->first();

        
        if($companyHasSameCampaignThisYear->count()){
            return back()->with('message', "Sua empresa já cadastrou uma campanha de testes de ". $companyHasSameCampaignThisYear->collection->name ." em 2025.");
        }

        $this->campaignRepository->update($campaign, $request);

        return to_route('campaign.index')->with('message', 'Campanha editada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyCampaign $campaign)
    {
        $this->campaignRepository->destroy($campaign);

        return to_route('campaign.index')->with('message', 'Campanha excluída com sucesso.');
    }
}
