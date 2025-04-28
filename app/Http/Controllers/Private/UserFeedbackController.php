<?php

namespace App\Http\Controllers\Private;

use App\Models\UserFeedback;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFeedbackController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userFeedbacks = session('company')
            ->users()
            ->whereHas('feedbacks', function ($query) use ($request) {
                $query->whereYear('created_at', $request->year ?? Carbon::now()->year);
            })
            ->select('users.id', 'name')
            ->hasAttribute('name', 'like', "%$request->name%")
            ->hasAttribute('cpf', 'like', "%$request->cpf%")
            ->hasAttribute('gender', '=', $request->gender)
            ->hasAttribute('department', '=', $request->department)
            ->hasAttribute('occupation', '=', $request->occupation)
            ->with('feedbacks')
            ->get();

        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        return view('private.dashboard.feedbacks.index', [
            'userFeedbacks' => $userFeedbacks,
            'filtersApplied' => $filtersApplied,
            'filteredUsers' => count($userFeedbacks) > 0 ? $userFeedbacks : null,
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
            return to_route('test.thanks');
        }

        UserFeedback::create([
            'company_id' => session('company')->id,
            'user_id' => Auth::user()->id,
            'content' => $validatedData['feedback'],
        ]);

        return to_route('test.thanks');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserFeedback $feedback)
    {
        $parentUser = $feedback->parentUser;
        $otherFeedbacksFromSameUser = $parentUser->feedbacks->reject(fn ($item) => $item->id == $feedback->id);

        return view('private.dashboard.feedbacks.show', compact('feedback', 'parentUser', 'otherFeedbacksFromSameUser'));
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
