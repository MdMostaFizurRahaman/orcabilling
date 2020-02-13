<?php

namespace App\Mail;

use App\System\Company;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct(Company $company)
    // {
    //     $this->company = $company;
    // }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(['address' => config('mail.address'), 'name' => Str::title(str_replace('-', ' ', config('mail.name')))])
                ->markdown('emails.test');
        // return $this->markdown('emails.test');
    }
}
