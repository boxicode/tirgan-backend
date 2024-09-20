<?php

namespace Modules\Auth\App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\App\Emails\UserSignupEmail;
use Modules\Auth\App\Events\SignedupProcessed;

class SendSignupEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(SignedupProcessed $event): void
    {
        $user = $event->user;

        Mail::to($user->email)->send(new UserSignupEmail($user));
    }
}
