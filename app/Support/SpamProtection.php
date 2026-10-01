<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Throwable;

class SpamProtection
{
    /**
     * Minimum seconds required between form render and submission.
     * Can be set to 0 in tests when testing other features.
     */
    public static int $minSeconds = 2;

    /**
     * Generate an encrypted timestamp token for time-based spam checking.
     */
    public static function generateToken(): string
    {
        return Crypt::encryptString((string) time());
    }

    /**
     * Validate honeypot, time-based check, and captcha hooks.
     *
     * @throws ValidationException
     */
    public static function verify(Request $request, string $honeypotField = '_hp_website', string $timeField = '_form_time'): void
    {
        // 1. Honeypot Check
        if (filled($request->input($honeypotField))) {
            throw ValidationException::withMessages([
                $honeypotField => ['Spam submission detected.'],
            ]);
        }

        // 2. Time-based Check
        $timeToken = $request->input($timeField);
        if (filled($timeToken) && static::$minSeconds > 0) {
            try {
                $renderedAt = (int) Crypt::decryptString((string) $timeToken);
                $elapsed = time() - $renderedAt;

                if ($elapsed < static::$minSeconds) {
                    throw ValidationException::withMessages([
                        'form' => ['Please take your time when filling out the form.'],
                    ]);
                }
            } catch (ValidationException $e) {
                throw $e;
            } catch (Throwable) {
                throw ValidationException::withMessages([
                    'form' => ['Invalid form submission token.'],
                ]);
            }
        }

        // 3. Cloudflare Turnstile Hook (if configured in .env)
        $turnstileSecret = config('services.turnstile.secret_key');
        if (filled($turnstileSecret)) {
            $turnstileToken = $request->input('cf-turnstile-response');
            if (blank($turnstileToken)) {
                throw ValidationException::withMessages([
                    'turnstile' => ['Please complete the security verification.'],
                ]);
            }

            try {
                $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $turnstileSecret,
                    'response' => $turnstileToken,
                    'remoteip' => $request->ip(),
                ]);

                if (! $response->json('success')) {
                    throw ValidationException::withMessages([
                        'turnstile' => ['Security verification failed. Please try again.'],
                    ]);
                }
            } catch (ValidationException $e) {
                throw $e;
            } catch (Throwable) {
                // Ignore external service network errors in production
            }
        }

        // 4. Google reCAPTCHA Hook (if configured in .env)
        $recaptchaSecret = config('services.recaptcha.secret_key');
        if (filled($recaptchaSecret)) {
            $recaptchaToken = $request->input('g-recaptcha-response');
            if (blank($recaptchaToken)) {
                throw ValidationException::withMessages([
                    'recaptcha' => ['Please complete the security check.'],
                ]);
            }

            try {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaToken,
                    'remoteip' => $request->ip(),
                ]);

                if (! $response->json('success')) {
                    throw ValidationException::withMessages([
                        'recaptcha' => ['reCAPTCHA verification failed. Please try again.'],
                    ]);
                }
            } catch (ValidationException $e) {
                throw $e;
            } catch (Throwable) {
                // Ignore external service network errors in production
            }
        }
    }
}
