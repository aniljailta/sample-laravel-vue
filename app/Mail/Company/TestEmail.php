<?php

namespace App\Mail\Company;

use Store;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @string
     */
    public $email;

    /**
     * Create a new message instance.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->email, 'Test account');
        $this->from(config('mail.from.address'), config('mail.from.name'));
        $this->subject('Test email');

        $companySettings = Store::get('company_settings');
        return $this->view('emails.company.test-email')->with('companySettings', $companySettings);
    }
}
