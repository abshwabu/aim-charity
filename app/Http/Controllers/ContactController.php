<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceivedMail;
use App\Models\ContactMessage;
use App\Support\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store an incoming public contact message.
     */
    public function store(ContactRequest $request): JsonResponse|RedirectResponse
    {
        $contactMessage = ContactMessage::create($request->safe()->only([
            'name',
            'email',
            'phone',
            'message',
        ]));

        $settings = Site::settings();
        $contactSettings = is_array($settings->contact) ? $settings->contact : [];
        $recipient = $contactSettings['notification_email'] ?? $contactSettings['email'] ?? config('mail.from.address');

        if (filled($recipient)) {
            Mail::to($recipient)->queue(new ContactMessageReceivedMail($contactMessage));
        }

        $sectionContent = is_array(Site::section('contact')->content) ? Site::section('contact')->content : [];
        $successMessage = $sectionContent['success_message'] ?? 'Thank you for reaching out! Your message has been sent to our coordination team.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('success', $successMessage);
    }
}
