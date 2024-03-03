<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|Response|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->expectsJson()
                ? response()->noContent()
                : redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return $request->expectsJson()
            ? response()->json(['status' => 'verification-link-sent'])
            : back()->with('status', 'verification-link-sent');
    }
}
