<?php

namespace App\Http\Controllers\UserProfile\Utilities;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user()->unreadNotifications;

        return response()->json($user);
    }

    public function detail(): JsonResponse
    {
        $notification = Auth::user()->unreadnotifications()->whereDate('created_at', Carbon::today())->get();

        return response()->json($notification);
    }

    public function markAsRead(): JsonResponse
    {
        $id = auth()->user()->unreadNotifications[0]->id;
        $test = auth()->user()->unreadNotifications->where('id', $id)->markAsRead();

        return response()->json([
            'success' => true,
            'message' => $test,
        ]);
    }
}
