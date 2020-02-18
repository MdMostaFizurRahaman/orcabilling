<?php

namespace App\Mail;

use App\System\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $media;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->media = (object) array('logo' => $this->company->getFirstMediaUrl('logo'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.company-test-mail')
                    ->from(config('mail.from.address'), strtoupper(str_replace('-', ' ', config('mail.from.name'))))
                    ->subject('Company Details');
    }
}
