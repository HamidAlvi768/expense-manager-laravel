<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LabReportNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientType;
    public $doctorName;
    public $messageBody;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipientType, $doctorName = null)
    {
        $this->recipientType = $recipientType;
        $this->doctorName = $doctorName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = 'emails.lab_report_notification';

        $subject = $this->recipientType === 'doctor'
            ? "Lab Report Completed"
            : "New Lab Report Notification";

    return $this->subject($subject)
        ->markdown($view, [
                    'recipientType' => $this->recipientType,
                        'doctorName' => $this->doctorName,

        ]);

     
    }
}