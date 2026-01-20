<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use Illuminate\Http\Request;

class ActivityHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLogs::with('user')->orderBy('id', 'desc');

        if ($request->has('filter') && $request->filter != 'all') {
            if ($request->filter == 'my_history') {
                // Show only current user's activities
                $query->where('user_id', auth()->id());
            } elseif ($request->filter == 'user_management') {
                $query->where('action', 'like', '%User%');
            } elseif ($request->filter == 'scan') {
                $query->where('action', 'like', '%Scan%');
            } elseif ($request->filter == 'simulation') {
                $query->where('action', 'like', '%simulation%');
            }
            // Add more filters as needed
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('activity.index', compact('logs'));
    }
}