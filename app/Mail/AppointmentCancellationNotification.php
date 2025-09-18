<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancellationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $recipientType;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Appointment  $appointment
     * @param  string  $recipientType
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
        switch ($this->recipientType) {
            case 'patient':
                $subject = "Appointment: " . $this->appointment->appointment_number . " Cancelled";
                break;
            case 'doctor':
                $subject = "Appointment has been cancelled from System";
                break;
            case 'admin':
                $subject = "Appointment Cancelled";
                break;
            default:
                $subject = "Appointment Cancelled";
                break;
        }
        return $this->subject($subject)
        ->markdown('emails.appointments.appointment_cancellation', [
            'appointment' => $this->appointment,
            'recipientType' => $this->recipientType,
        ]);
        // return $this->subject($subject)
        //             ->view('emails.appointments.appointment_cancellation')  
        //             ->with([
        //                 'appointment' => $this->appointment,
        //             ]);
    }
}