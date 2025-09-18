<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $userData;
    public $recipientType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData, $recipientType)
    {
        $this->userData = $userData;
        $this->recipientType = $recipientType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = '';
        $subject = '';

        switch ($this->recipientType) {
            case 'admin':
                $view = 'emails.doctor_registration';
                $subject = "New Doctor Registration";
                break;
            case 'doctor':
                $view = 'emails.doctor_registration';
                $subject = "Alshifa Dental Specialists - Registration";
                break;
        }
        
        return $this->subject($subject)
        ->markdown($view, [
            'userData' => $this->userData,
            'recipientType' => $this->recipientType,
        ]);
        
        // return $this->view($view)
        //             ->subject($subject)
        //             ->with([
        //                 'userData' => $this->userData,
        //             ]);
    }
}