<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Volunteer Application</title>
</head>
<body style="font-family: ui-sans-serif, system-ui, sans-serif; background-color: #fbf9f5; color: #1c1917; padding: 32px 16px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #e7e5e4; padding: 32px;">
        <h2 style="color: #1b4332; margin-top: 0; font-size: 22px;">New Volunteer Application Received</h2>
        <p style="color: #78716c; font-size: 14px; margin-bottom: 24px;">A new volunteer has applied to join the Aim Charity town association.</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
            <tr>
                <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600; width: 140px;">Name:</td>
                <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $application->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Email:</td>
                <td style="padding: 8px 0; color: #1c1917; font-size: 14px;"><a href="mailto:{{ $application->email }}" style="color: #1b4332;">{{ $application->email }}</a></td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Phone:</td>
                <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $application->phone }}</td>
            </tr>
            @if($application->memberGroup)
                <tr>
                    <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Member Group:</td>
                    <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $application->memberGroup->name }}</td>
                </tr>
            @endif
            @if(filled($application->skills))
                <tr>
                    <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Skills:</td>
                    <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $application->skills }}</td>
                </tr>
            @endif
            @if(filled($application->availability))
                <tr>
                    <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Availability:</td>
                    <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $application->availability }}</td>
                </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Date:</td>
                <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $application->created_at?->format('F j, Y, g:i a') }}</td>
            </tr>
        </table>

        @if(filled($application->message))
            <div style="background-color: #fbf9f5; border-radius: 8px; border: 1px solid #e7e5e4; padding: 16px; margin-bottom: 24px;">
                <p style="margin: 0; color: #78716c; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 8px;">Volunteer Note:</p>
                <p style="margin: 0; color: #1c1917; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $application->message }}</p>
            </div>
        @endif

        <p style="color: #a8a29e; font-size: 12px; margin-bottom: 0;">This notification was automatically sent by Aim Charity system.</p>
    </div>
</body>
</html>
