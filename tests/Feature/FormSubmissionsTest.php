<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\NewsletterSubscribers\NewsletterSubscriberResource;
use App\Filament\Resources\NewsletterSubscribers\Pages\ListNewsletterSubscribers;
use App\Filament\Resources\VolunteerApplications\Pages\EditVolunteerApplication;
use App\Filament\Resources\VolunteerApplications\VolunteerApplicationResource;
use App\Mail\ContactMessageReceivedMail;
use App\Mail\VolunteerApplicationReceivedMail;
use App\Models\ContactMessage;
use App\Models\MemberGroup;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Models\VolunteerApplication;
use App\Support\Site;
use App\Support\SpamProtection;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\PageSectionSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class FormSubmissionsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
            PageSectionSeeder::class,
            ItemSeeder::class,
        ]);

        Site::flushCache();
        $this->admin = User::query()->firstOrFail();

        // Allow instant submission by default in tests
        SpamProtection::$minSeconds = 0;
    }

    public function test_contact_form_submission_creates_record_and_queues_email(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'name' => 'Abebe Bikila',
            'email' => 'abebe@example.com',
            'phone' => '+251 911 000 000',
            'message' => 'I would love to learn more about partnering with Aim Charity.',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Abebe Bikila',
            'email' => 'abebe@example.com',
            'phone' => '+251 911 000 000',
            'message' => 'I would love to learn more about partnering with Aim Charity.',
            'read_at' => null,
        ]);

        Mail::assertQueued(ContactMessageReceivedMail::class, function (ContactMessageReceivedMail $mail) {
            $settings = Site::settings();
            $contactSettings = is_array($settings->contact) ? $settings->contact : [];
            $expectedRecipient = $contactSettings['notification_email'] ?? $contactSettings['email'] ?? config('mail.from.address');

            return $mail->contactMessage->email === 'abebe@example.com' &&
                $mail->hasTo($expectedRecipient);
        });
    }

    public function test_contact_form_ajax_submission_returns_json(): void
    {
        Mail::fake();

        $response = $this->postJson('/contact', [
            'name' => 'Tirunesh Dibaba',
            'email' => 'tirunesh@example.com',
            'message' => 'How can our athletics club donate sports equipment?',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'tirunesh@example.com',
        ]);

        Mail::assertQueued(ContactMessageReceivedMail::class);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->postJson('/contact', [
            'email' => 'invalid-email',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    public function test_volunteer_form_submission_creates_record_and_queues_email(): void
    {
        Mail::fake();

        $group = MemberGroup::query()->firstOrFail();

        $response = $this->post('/volunteer', [
            'name' => 'Haile Gebrselassie',
            'email' => 'haile@example.com',
            'phone' => '+251 912 345 678',
            'member_group_id' => $group->id,
            'skills' => 'Event coordination, logistics',
            'availability' => 'Weekends & evenings',
            'message' => 'Ready to support food distribution and youth mentorship.',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('volunteer_applications', [
            'name' => 'Haile Gebrselassie',
            'email' => 'haile@example.com',
            'member_group_id' => $group->id,
            'skills' => 'Event coordination, logistics',
            'status' => VolunteerApplication::STATUS_NEW,
        ]);

        Mail::assertQueued(VolunteerApplicationReceivedMail::class, function (VolunteerApplicationReceivedMail $mail) {
            $settings = Site::settings();
            $contactSettings = is_array($settings->contact) ? $settings->contact : [];
            $expectedRecipient = $contactSettings['notification_email'] ?? $contactSettings['email'] ?? config('mail.from.address');

            return $mail->application->email === 'haile@example.com' &&
                $mail->hasTo($expectedRecipient);
        });
    }

    public function test_volunteer_form_ajax_submission_returns_json(): void
    {
        Mail::fake();

        $response = $this->postJson('/volunteer', [
            'name' => 'Derartu Tulu',
            'email' => 'derartu@example.com',
            'phone' => '+251 911 223 344',
            'skills' => 'Community outreach',
            'availability' => '10 hours per week',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('volunteer_applications', [
            'name' => 'Derartu Tulu',
            'email' => 'derartu@example.com',
            'member_group_id' => null,
            'status' => VolunteerApplication::STATUS_NEW,
        ]);

        Mail::assertQueued(VolunteerApplicationReceivedMail::class);
    }

    public function test_volunteer_form_validates_required_fields(): void
    {
        $response = $this->postJson('/volunteer', [
            'email' => 'invalid-email',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'phone']);
    }

    public function test_newsletter_subscription_creates_record(): void
    {
        $response = $this->post('/newsletter', [
            'email' => 'subscriber@example.org',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.org',
        ]);
    }

    public function test_newsletter_subscription_is_idempotent_for_existing_subscriber(): void
    {
        NewsletterSubscriber::query()->create([
            'email' => 'existing@example.org',
            'subscribed_at' => now(),
        ]);

        $response = $this->postJson('/newsletter', [
            'email' => 'existing@example.org',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertSame(1, NewsletterSubscriber::query()->where('email', 'existing@example.org')->count());
    }

    public function test_honeypot_rejects_spambot_submissions(): void
    {
        $response = $this->postJson('/contact', [
            'name' => 'Bot Spammer',
            'email' => 'bot@spammer.net',
            'message' => 'Buy cheap links now!',
            '_hp_website' => 'http://spam-link.example.com',
            '_form_time' => SpamProtection::generateToken(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['_hp_website']);
    }

    public function test_fast_submission_rejects_automated_posts_when_min_seconds_configured(): void
    {
        SpamProtection::$minSeconds = 3;

        // Generate a token from right now (0 seconds elapsed)
        $token = SpamProtection::generateToken();

        $response = $this->postJson('/contact', [
            'name' => 'Speedy Bot',
            'email' => 'speedy@bot.net',
            'message' => 'Fastest submit in the world!',
            '_form_time' => $token,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['form']);
    }

    public function test_admin_can_view_contact_messages_list_and_badge_updates(): void
    {
        $this->actingAs($this->admin);

        ContactMessage::query()->create([
            'name' => 'Unread Visitor',
            'email' => 'unread@example.com',
            'message' => 'Need urgent information regarding donations.',
            'read_at' => null,
        ]);

        ContactMessage::query()->create([
            'name' => 'Read Visitor',
            'email' => 'read@example.com',
            'message' => 'Thank you for following up.',
            'read_at' => now(),
        ]);

        $this->assertSame('1', ContactMessageResource::getNavigationBadge());

        $response = $this->get('/admin/contact-messages');
        $response->assertOk();
        $response->assertSee('Unread Visitor');
        $response->assertSee('Read Visitor');
    }

    public function test_admin_viewing_contact_message_marks_it_as_read(): void
    {
        $this->actingAs($this->admin);

        $message = ContactMessage::query()->create([
            'name' => 'Inquiring Donor',
            'email' => 'donor@example.com',
            'message' => 'Please provide direct bank wire instructions.',
            'read_at' => null,
        ]);

        $this->assertTrue($message->isUnread());

        $response = $this->get("/admin/contact-messages/{$message->id}");
        $response->assertOk();
        $response->assertSee('Inquiring Donor');

        $message->refresh();
        $this->assertFalse($message->isUnread());
        $this->assertNotNull($message->read_at);
    }

    public function test_admin_can_view_volunteer_applications_and_update_status_and_notes(): void
    {
        $this->actingAs($this->admin);

        $app = VolunteerApplication::query()->create([
            'name' => 'Kenenisa Bekele',
            'email' => 'kenenisa@example.com',
            'phone' => '+251 911 234 567',
            'status' => VolunteerApplication::STATUS_NEW,
            'notes' => null,
        ]);

        $this->assertSame('1', VolunteerApplicationResource::getNavigationBadge());

        $response = $this->get('/admin/volunteer-applications');
        $response->assertOk();
        $response->assertSee('Kenenisa Bekele');

        Livewire::test(EditVolunteerApplication::class, ['record' => (string) $app->id])
            ->fillForm([
                'status' => VolunteerApplication::STATUS_ACCEPTED,
                'notes' => 'Completed initial screening phone call. Highly enthusiastic.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $app->refresh();
        $this->assertSame(VolunteerApplication::STATUS_ACCEPTED, $app->status);
        $this->assertStringContainsString('Highly enthusiastic', $app->notes);
    }

    public function test_admin_can_view_newsletter_subscribers_and_download_csv(): void
    {
        $this->actingAs($this->admin);

        NewsletterSubscriber::query()->create([
            'email' => 'export1@example.com',
            'subscribed_at' => now(),
        ]);

        NewsletterSubscriber::query()->create([
            'email' => 'export2@example.com',
            'subscribed_at' => now(),
        ]);

        $this->assertSame('2', NewsletterSubscriberResource::getNavigationBadge());

        $response = $this->get('/admin/newsletter-subscribers');
        $response->assertOk();
        $response->assertSee('export1@example.com');
        $response->assertSee('export2@example.com');

        Livewire::test(ListNewsletterSubscribers::class)
            ->callTableAction('exportCsv')
            ->assertFileDownloaded('newsletter-subscribers-'.now()->format('Y-m-d').'.csv');
    }
}
