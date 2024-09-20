<?php

namespace Modules\Auth\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Auth\App\Events\SignedupProcessed;
use Modules\Auth\App\Listeners\SendSignupEmail;

class EventsServiceProvider extends ServiceProvider
{
    protected $listen = [

        SignedupProcessed::class => [
            SendSignupEmail::class,
        ],

    ];
}
