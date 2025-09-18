<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $userData;
    public $patientDetails;
    public $recipientType; // 'patient' or 'admin'

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData, $patientDetails, $recipientType)
    {
        $this->userData = $userData;
        $this->patientDetails = $patientDetails;
        $this->recipientType = $recipientType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->getSubject())
                    ->markdown('emails.patient_registration')
                    ->with([
                        'userName' => $this->userData['name'],
                        'mrnNumber' => $this->patientDetails['mrn_number'],
                        'recipientType' => $this->recipientType,
                    ]);
    }

    /**
     * Get the subject for the email.
     *
     * @return string
     */
    protected function getSubject()
    {
        return $this->recipientType === 'admin'
            ? "Patient registration MRN# " . $this->patientDetails['mrn_number']
            : "Alshifa Dental Specialists - Registration with MRN ".$this->patientDetails['mrn_number'];
    }
}