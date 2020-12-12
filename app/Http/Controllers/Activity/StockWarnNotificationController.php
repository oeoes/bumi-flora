<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Activity\StockWarnNotification;

class StockWarnNotificationController extends Controller
{
    public function index () {
        $data = StockWarnNotification::where('is_read', 0)->orderBy('urgency', 'DESC')->get();
        $messages = StockWarnNotification::where('is_read', 0)->orderBy('urgency', 'DESC')->limit(6)->get();
        if ($data) $data->makeHidden(['created_at', 'updated_at']);

        return response()->json(['status' => count($data) ? true : false, 'message' => 'list of notification', 'data' => $messages, 'count' => count($data)]);
    }

    public function notification_page () {
        $notifications = StockWarnNotification::where('is_read', 0)->orderBy('urgency', 'DESC')->get();
        return view('pages.activity.notification-page')->with(['notifications' => $notifications]);
    }

    public function destroy (StockWarnNotification $notification) {
        $notification->update(['is_read' => 1]);
        return back();
    }
}
