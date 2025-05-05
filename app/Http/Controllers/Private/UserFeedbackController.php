<?php

namespace App\Http\Controllers\Private;

use App\Models\UserFeedback;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserFeedbackController
{
    protected $filterService;

    public function __construct(UserFilterService $filterService){
        $this->filterService = $filterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view-manager-screens');

        // Catching users
        $query = session('company')
            ->users()
            ->whereHas('feedbacks', function ($query) use ($request) {
                $query->whereYear('created_at', $request->year ?? Carbon::now()->year);
            })
            ->select('users.id', 'name', 'department', 'work_shift')
            ->getQuery();

        // Applying filters
        $query = $query
            ->hasAttribute('name', 'like', "%$request->name%")
            ->hasAttribute('cpf', 'like', "%$request->cpf%")
            ->hasAttribute('gender', '=', $request->gender)
            ->hasAttribute('department', '=', $request->department)
            ->hasAttribute('occupation', '=', $request->occupation);

        $query = $this->filterService->applyAgeRange($query, $request->age_range);
        $query = $this->filterService->applyAdmissionRange($query, $request->admission_range);

        $userFeedbacks = $query
            ->with('feedbacks', fn($query) => $query->latest()->limit(1))
            ->get();

        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        return view('private.dashboard.feedbacks.index', [
            'userFeedbacks' => $userFeedbacks,
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => count($userFeedbacks) > 0 ? count($userFeedbacks) : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('private.tests.feedback');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'feedback' => 'nullable|string|min:12',
        ]);

        if ($validatedData['feedback'] == null) {
            return to_route('responder-teste.thanks');
        }

        UserFeedback::create([
            'company_id' => session('company')->id,
            'user_id' => Auth::user()->id,
            'content' => $validatedData['feedback'],
        ]);

        return to_route('responder-teste.thanks');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserFeedback $feedback)
    {
        Gate::authorize('view-manager-screens');

        $parentUser = $feedback->parentUser;

        return view('private.dashboard.feedbacks.show', compact('feedback', 'parentUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserFeedback $userFeedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserFeedback $userFeedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserFeedback $userFeedback)
    {
        //
    }
}
