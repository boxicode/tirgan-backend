<?php

namespace Modules\Auth\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Auth\App\Models\User;

class UserSignupEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('auth::emails.signup')->subject('ثبت نام کاربر جدید')->with([
            'first_name' => $this->user->first_name,
            'activation_token' => $this->user->activation_token,
        ]);
    }
}
