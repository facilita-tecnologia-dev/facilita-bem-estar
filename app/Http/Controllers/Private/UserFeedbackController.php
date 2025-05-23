<?php

namespace App\Http\Controllers\Private;

use App\Helpers\AuthGuardHelper;
use App\Models\UserFeedback;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserFeedbackController
{
    protected $filterService;

    public function __construct(UserFilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('feedbacks-index');
        $userFeedbacks = $this->query($request);
        $filteredUserCount = $userFeedbacks->total();
        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        return view('private.dashboard.feedbacks.index', [
            'userFeedbacks' => $userFeedbacks->count() ? $userFeedbacks : null,
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => $filteredUserCount ? $filteredUserCount : null,
        ]);
    }

    private function query(Request $request)
    {
        $query = session('company')->users()->getQuery();

        return $this->filterService->sort($this->filterService->apply($query))
            ->whereHas('feedbacks', function ($query) use ($request) {
                $query->whereYear('created_at', $request->year ?? Carbon::now()->year);
            })
            ->select('users.id', 'name', 'department', 'work_shift')
            ->with('feedbacks', fn ($query) => $query->latest()->limit(1))
            ->paginate(15)->appends(request()->query());
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
            'user_id' => AuthGuardHelper::user()->id,
            'content' => $validatedData['feedback'],
        ]);

        return to_route('responder-teste.thanks');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserFeedback $feedback)
    {
        Gate::authorize('feedbacks-index');
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
