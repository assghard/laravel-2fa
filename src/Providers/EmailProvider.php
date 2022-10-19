<?php

declare(strict_types=1);

namespace Assghard\Laravel2fa\Providers;

use Illuminate\Support\Facades\Mail;

class EmailProvider
{
    public function sendNotification(string $email, string $textMessage): bool 
    {
        try {
            Mail::send([], [], function ($message) use ($email, $textMessage) {
                $message->to($email)->subject(__('2fa.messages.2fa_email_subject'))->setBody($textMessage);
            });

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
