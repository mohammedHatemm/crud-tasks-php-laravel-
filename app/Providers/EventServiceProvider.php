<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\LogSuccessfulLogin;

use Illuminate\Auth\Events\Registered;
use App\Listeners\LogUserRegistered;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogSuccessfulLogin::class,
        ],
        Registered::class => [
            LogUserRegistered::class,
        ],
    ];
}
