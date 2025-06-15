<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\YourCustomMail; // تأكد من وجود هذا الموديل للإيميل
class LogUserRegistered
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        Log::info("New user registered: " . $user->email);

        // لو حابب تبعت إيميل ترحيبي:
        Mail::to($user->email)->send(new YourCustomMail($user));
    }
}
