<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignStoreRequest;
use App\Http\Requests\CampaignUpdateRequest;
use App\Mail\CampaignEmail;
use App\Models\Collection;
use App\Models\Company;
use App\Models\CompanyCampaign;
use App\Models\CustomCollection;
use App\Models\User;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class CompanyCampaignController
{
    protected $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    public function index()
    {
        Gate::authorize('campaign-index');
        $companyCampaigns = Company::firstWhere('id', session('company')->id)->campaigns()->with('collection')->paginate(15);

        return view('private.campaign.index', compact('companyCampaigns'));
    }

    public function create()
    {
        Gate::authorize('campaign-create');
   
        $collectionsToSelect = session('company')['customCollections']
        ->map(fn ($collection) => [
            'option' => $collection['collection_id'] == 2 ? $collection->name . ' (Clima Organizacional)' :  $collection->name,
            'value' => $collection->id,
        ]);

        return view('private.campaign.create', compact('collectionsToSelect'));
    }

    public function store(CampaignStoreRequest $request)
    {
        Gate::authorize('campaign-create');

        $psychosocialCollection = session('company')['customCollections']->firstWhere('collection_id', 1);

        $collectionId = request('collection_id') == $psychosocialCollection['id'] ? 1 : 2;

        $companyHasSameCampaignThisYear = session('company')->hasCampaignThisYear($collectionId);
        
        if ($companyHasSameCampaignThisYear) {
            return back()->with('message', 'Sua empresa já cadastrou uma campanha de testes de '.$companyHasSameCampaignThisYear->collection->name.' em 2025.');
        }

        $this->campaignRepository->store($request);

        return to_route('campaign.index');
    }

    public function show(CompanyCampaign $campaign)
    {
        Gate::authorize('campaign-show');
        
        $hasCollectionTestsThisYear = Company::firstWhere('id', session('company')->id)->users()
        ->whereHas('collections', function ($query) use($campaign) {
            $query->where('collection_id', $campaign->collection_id);
        })
        ->exists();

        return view('private.campaign.show', [
            'campaign' => $campaign,
            'hasCollectionTestsThisYear' => $hasCollectionTestsThisYear,
        ]);
    }

    public function edit(CompanyCampaign $campaign)
    {
        Gate::authorize('campaign-edit');
        $collectionsToSelect = Collection::all()->map(fn ($collection) => [
            'option' => $collection->name,
            'value' => $collection->id,
        ]);

        return view('private.campaign.edit', compact('campaign', 'collectionsToSelect'));
    }

    public function update(CampaignUpdateRequest $request, CompanyCampaign $campaign)
    {
        Gate::authorize('campaign-edit');
        $companyHasSameCampaignThisYear = Company::firstWhere('id', session('company')->id)
            ->campaigns()
            ->whereYear('start_date', now()->year)
            ->where('collection_id', $request->validated('collection_id'))
            ->first();

        if ($companyHasSameCampaignThisYear->count() && $companyHasSameCampaignThisYear->id !== $campaign->id) {
            return back()->with('message', 'Sua empresa já cadastrou uma campanha de testes de '.$companyHasSameCampaignThisYear->collection->name.' em 2025.');
        }

        $this->campaignRepository->update($campaign, $request);

        return to_route('campaign.index')->with('message', 'Campanha editada com sucesso.');
    }

    public function destroy(CompanyCampaign $campaign)
    {
        Gate::authorize('campaign-delete');
        $this->campaignRepository->destroy($campaign);

        return to_route('campaign.index')->with('message', 'Campanha excluída com sucesso.');
    }

    public function dispatchNotifications(CompanyCampaign $campaign)
    {
        $usersWithEmail = session('company')->users->where('email');
        
        $usersWithEmail->each(function($user) use($campaign) {
            Mail::to($user->email)->queue(new CampaignEmail($user, session('company'), $campaign));
        });
        
        // return new CampaignEmail($usersWithEmail->first(), session('company'), $campaign);
        return back()->with('message', 'Notificações disparadas com sucesso!');
    }
}
