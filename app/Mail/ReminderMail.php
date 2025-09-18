<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $recipientType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($appointment, $recipientType)
    {
        $this->appointment = $appointment;
        $this->recipientType = $recipientType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = '';

        switch ($this->recipientType) {
            case 'doctor':
                $subject = "Appointment Reminder for Dr. " . $this->appointment->doctor->name;
                break;
            case 'patient':
                $subject = "Appointment Reminder for " . $this->appointment->user->name;
                break;
        }
      return $this->subject($subject)
            ->markdown('emails.appointments.reminder', [
                'appointment' => $this->appointment,
                'recipientType' => $this->recipientType,
            ]);
     
    }
}