<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
</head>
<body style="font-family: ui-sans-serif, system-ui, sans-serif; background-color: #fbf9f5; color: #1c1917; padding: 32px 16px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #e7e5e4; padding: 32px;">
        <h2 style="color: #1b4332; margin-top: 0; font-size: 22px;">New Contact Message Received</h2>
        <p style="color: #78716c; font-size: 14px; margin-bottom: 24px;">A new inquiry has been submitted through the Aim Charity website.</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
            <tr>
                <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600; width: 120px;">Name:</td>
                <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $contactMessage->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Email:</td>
                <td style="padding: 8px 0; color: #1c1917; font-size: 14px;"><a href="mailto:{{ $contactMessage->email }}" style="color: #1b4332;">{{ $contactMessage->email }}</a></td>
            </tr>
            @if(filled($contactMessage->phone))
                <tr>
                    <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Phone:</td>
                    <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $contactMessage->phone }}</td>
                </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #78716c; font-size: 13px; font-weight: 600;">Date:</td>
                <td style="padding: 8px 0; color: #1c1917; font-size: 14px;">{{ $contactMessage->created_at?->format('F j, Y, g:i a') }}</td>
            </tr>
        </table>

        <div style="background-color: #fbf9f5; border-radius: 8px; border: 1px solid #e7e5e4; padding: 16px; margin-bottom: 24px;">
            <p style="margin: 0; color: #78716c; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 8px;">Message:</p>
            <p style="margin: 0; color: #1c1917; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $contactMessage->message }}</p>
        </div>

        <p style="color: #a8a29e; font-size: 12px; margin-bottom: 0;">This notification was automatically sent by Aim Charity system.</p>
    </div>
</body>
</html>
