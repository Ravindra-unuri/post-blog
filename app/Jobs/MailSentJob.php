<?php

namespace App\Jobs;

use App\Mail\SendMailTest;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MailSentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    protected $worker_name= 'MailSent_worker';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {

        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email= new SendMailTest();
        mail::to($this->user['email'])->send($email);
    }
}
    