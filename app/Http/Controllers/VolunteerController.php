<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\VolunteerApplicationRequest;
use App\Mail\VolunteerApplicationReceivedMail;
use App\Models\VolunteerApplication;
use App\Support\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class VolunteerController extends Controller
{
    /**
     * Store an incoming volunteer application.
     */
    public function store(VolunteerApplicationRequest $request): JsonResponse|RedirectResponse
    {
        $application = VolunteerApplication::create(array_merge(
            $request->safe()->only([
                'name',
                'email',
                'phone',
                'member_group_id',
                'skills',
                'availability',
                'message',
            ]),
            ['status' => VolunteerApplication::STATUS_NEW]
        ));

        $settings = Site::settings();
        $contactSettings = is_array($settings->contact) ? $settings->contact : [];
        $recipient = $contactSettings['notification_email'] ?? $contactSettings['email'] ?? config('mail.from.address');

        if (filled($recipient)) {
            Mail::to($recipient)->queue(new VolunteerApplicationReceivedMail($application));
        }

        $sectionContent = is_array(Site::section('volunteer')->content) ? Site::section('volunteer')->content : [];
        $successMessage = $sectionContent['success_message'] ?? 'Thank you for stepping up to help! Our volunteer coordinator will reach out to you within 48 hours.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('success', $successMessage);
    }
}
