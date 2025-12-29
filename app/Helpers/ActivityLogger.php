<?php

namespace App\Helpers;

use App\Models\ActivityLogs;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $details = null)
    {
        ActivityLogs::create([
            'user_id' => Auth::id(),
            'action'  => $action,
            'details' => $details,
        ]);
    }
}
