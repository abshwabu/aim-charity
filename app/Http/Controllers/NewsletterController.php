<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    /**
     * Store an incoming newsletter subscription.
     */
    public function store(NewsletterSubscribeRequest $request): JsonResponse|RedirectResponse
    {
        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->validated('email')],
            ['subscribed_at' => now()]
        );

        $successMessage = 'Thank you for subscribing to Aim Charity coalition updates!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('success', $successMessage);
    }
}
