<?php

namespace Tests\Feature;

use App\Mail\ContactAutoReplyMail;
use App\Mail\NewContactRequestMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormSubmissionTest extends TestCase
{
    public function test_contact_form_sends_admin_and_auto_reply_emails(): void
    {
        Mail::fake();

        $response = $this->postJson(route('contact.submit'), [
            'name' => 'Janis Berzins',
            'phone' => '+37128842265',
            'email' => 'janis@example.com',
            'service' => 'Renovācijas un iekšdarbi',
            'message' => 'Vēlos saņemt piedāvājumu.',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Paldies! Jūsu pieteikums ir nosūtīts. Mēs ar Jums sazināsimies tuvākajā laikā.',
            ]);

        Mail::assertSent(NewContactRequestMail::class);
        Mail::assertSent(ContactAutoReplyMail::class);
    }

    public function test_contact_form_returns_success_without_auto_reply_when_email_is_missing(): void
    {
        Mail::fake();

        $response = $this->postJson(route('contact.submit'), [
            'name' => 'Janis Berzins',
            'phone' => '+37128842265',
            'service' => 'Renovācijas un iekšdarbi',
            'message' => 'Vēlos saņemt piedāvājumu.',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        Mail::assertSent(NewContactRequestMail::class);
        Mail::assertNotSent(ContactAutoReplyMail::class);
    }

    public function test_contact_form_returns_validation_errors_for_invalid_payload(): void
    {
        Mail::fake();

        $response = $this->postJson(route('contact.submit'), []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'phone', 'message']);

        Mail::assertNothingSent();
    }

    public function test_dev_test_mail_route_is_not_available_outside_local_environment(): void
    {
        Mail::fake();

        $originalEnvironment = config('app.env');
        config(['app.env' => 'production']);

        $this->get('/dev/test-mail')->assertNotFound();

        config(['app.env' => $originalEnvironment]);
    }
}
