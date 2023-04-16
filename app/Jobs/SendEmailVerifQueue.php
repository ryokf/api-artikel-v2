<?php

namespace App\Jobs;

use App\Mail\VerifEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendEmailVerifQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $hashedEmail;
    public $email;
    public $username;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $email,
        $hashedEmail,
        $username
    ) {
        $this->email = $email;
        $this->hashedEmail = $hashedEmail;
        $this->username = $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new VerifEmail($this->hashedEmail, $this->username));
    }
}
