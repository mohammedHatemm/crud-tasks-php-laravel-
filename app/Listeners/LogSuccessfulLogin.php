<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\YourCustomMail;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // سجل الإيميل في اللوج
        Log::info("User logged in: " . $user->email);

        // لو حابب تبعت إيميل:
        Mail::to($user->email)->send(new YourCustomMail($user));
    }
}
