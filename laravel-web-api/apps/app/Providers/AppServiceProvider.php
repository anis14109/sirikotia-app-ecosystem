<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event; // Import the Event facade
use Illuminate\Mail\Events\MessageSending; // Import the MessageSending event

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Listen to every outgoing email right before it sends
        Event::listen(MessageSending::class, function (MessageSending $event) {

            // Add the BCC address if it exists in your .env file
            $bccAddress = env('MAIL_GLOBAL_BCC');
            if ($bccAddress) {
                $event->message->addBcc($bccAddress);
            }
        });
    }
}
