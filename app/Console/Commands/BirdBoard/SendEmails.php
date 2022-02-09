<?php

namespace App\Console\Commands\BirdBoard;

use App\Models\User;
use Illuminate\Console\Command;
use App\Mail\BirdBoard\DripMail;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birdboard:send-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'Send drip e-mails to a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::firstWhere('email', $this->argument('email'));
        Mail::to($user)->send(new DripMail($user));
    }
}
