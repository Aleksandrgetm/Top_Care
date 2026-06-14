<?php

namespace App\Http\Controllers;

use App\Mail\ContactAutoReplyMail;
use App\Mail\NewContactRequestMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function submit(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'name.required' => 'Lūdzu, ievadiet savu vārdu.',
            'phone.required' => 'Lūdzu, ievadiet tālruņa numuru.',
            'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
            'message.required' => 'Lūdzu, aprakstiet nepieciešamos darbus.',
        ]);

        $data['service'] = filled($data['service'] ?? null) ? $data['service'] : 'Nav norādīts';
        $data['email'] = filled($data['email'] ?? null) ? $data['email'] : null;
        $data['source_page'] = 'topcare.lv';
        $data['submitted_at'] = now();

        $successMessage = 'Paldies! Jūsu pieteikums ir nosūtīts. Mēs ar Jums sazināsimies tuvākajā laikā.';
        $errorMessage = 'Neizdevās nosūtīt pieteikumu. Lūdzu, mēģiniet vēlreiz vai sazinieties ar mums pa tālruni.';

        try {
            Mail::to('topcare.lv@gmail.com')
                ->send(new NewContactRequestMail($data));

            Log::info('Admin contact email sent', [
                'to' => 'topcare.lv@gmail.com',
                'client_email' => $data['email'] ?? null,
                'service' => $data['service'],
                'source_page' => $data['source_page'],
                'submitted_at' => $data['submitted_at']->toDateTimeString(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Admin contact email failed', [
                'message' => $e->getMessage(),
                'client_email' => $data['email'] ?? null,
                'service' => $data['service'],
                'source_page' => $data['source_page'],
                'submitted_at' => $data['submitted_at']->toDateTimeString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['contact' => $errorMessage]);
        }

        if (! empty($data['email'])) {
            try {
                Mail::to($data['email'])
                    ->send(new ContactAutoReplyMail($data));

                Log::info('Client auto reply sent', [
                    'to' => $data['email'],
                    'source_page' => $data['source_page'],
                    'submitted_at' => $data['submitted_at']->toDateTimeString(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('Client auto reply failed', [
                    'message' => $e->getMessage(),
                    'client_email' => $data['email'],
                    'source_page' => $data['source_page'],
                    'submitted_at' => $data['submitted_at']->toDateTimeString(),
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('success', $successMessage);
    }

    public function testMail(): View
    {
        abort_unless(app()->environment('local'), 404);

        $data = [
            'name' => 'Testa klients',
            'phone' => '+371 28 842 265',
            'email' => 'klients@example.com',
            'service' => 'Renovācijas un iekšdarbi',
            'message' => 'Šis ir testa pieteikums, lai pārbaudītu e-pasta izskatu un Mailtrap konfigurāciju.',
            'source_page' => 'topcare.lv',
            'submitted_at' => now(),
        ];

        Mail::to('topcare.lv@gmail.com')->send(new NewContactRequestMail($data));
        Mail::to($data['email'])->send(new ContactAutoReplyMail($data));

        return view('emails.contact.test-sent');
    }
}
