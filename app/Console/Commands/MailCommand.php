<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\HelloEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $res = $this->sendEmail();
        echo $res;
        return Command::SUCCESS;
    }

    public function sendEmail()
    {
        /** 
         * Store a receiver email address to a variable.
         */
        $reveiverEmailAddress = "oleg.shmyk@gmail.com";

        /**
         * Import the Mail class at the top of this page,
         * and call the to() method for passing the 
         * receiver email address.
         * 
         * Also, call the send() method to incloude the
         * HelloEmail class that contains the email template.
         */
        $is_sended = Mail::to($reveiverEmailAddress)->send(new HelloEmail);

        /**
         * Check if the email has been sent successfully, or not.
         * Return the appropriate message.
         */
        if ($is_sended) {
            return "Email has been sent successfully.";
        } else {
            return "Oops! There was some error sending the email.";
        }

        return "Oops! There was some error sending the email.";
    }
}
