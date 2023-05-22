<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotEmail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:forgot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test mail service MailGun';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $to_email = "aleh.shmyk@gmail.com";

        Mail::to($to_email)->send(new ForgotEmail('Pasek Toretto'));

        if (!Mail::flushMacros()) {
            return "<p> Success! Your E-mail has been sent.</p>";
        } else {
            return "<p> Failed! Your E-mail has not sent.</p>";
        }
        return Command::SUCCESS;
    }
}
