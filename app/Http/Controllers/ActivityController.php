<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display user's activity history (for operators)
     */
    public function index(Request $request)
    {
        $query = Activity::with('user')
                        ->where('user_id', auth()->id());

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->latest()->paginate(10);
        $actions = Activity::distinct()->pluck('action');

        return view('activities.index', compact('activities', 'actions'));
    }
}
