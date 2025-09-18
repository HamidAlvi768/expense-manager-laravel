<?php

namespace App\Mail;

use App\Models\PatientAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAppointment extends Mailable
{
    // use Queueable, SerializesModels;

    // /**
    //  * new appointment.
    //  *
    //  * @var App\Models\PatientAppointment
    //  */
    // protected $patientAppointment;

    // /**
    //  * Create a new message instance.
    //  *
    //  * @return void
    //  */
    // public function __construct(PatientAppointment $patientAppointment)
    // {
    //     $this->patientAppointment = $patientAppointment;
    // }

    // /**
    //  * Build the message.
    //  *
    //  * @return $this
    //  */
    // public function build()
    // {
    //     return $this->subject('Appointment booked successfully')
    //         ->markdown('emails.appointments.newappointment', [
    //             'patientAppointment' => $this->patientAppointment
    //         ]);
    // }
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
                $subject = "Alshifa Dental Specialists Appointment " . $this->appointment->appointment_number;
                $view = 'emails.appointments.newappointment';
                break;
            case 'doctor':
                $subject = "A New Appointment Booked ". $this->appointment->appointment_number;
                $view = 'emails.appointments.newappointment';
                break;
            case 'admin':
                $subject = "A New Appointment Added to System";
                $view = 'emails.appointments.newappointment';
                break;
            default:
                $subject = "Alshifa Dental Specialists Appointment " . $this->appointment->appointment_number;
                $view = 'emails.appointments.newappointment';
                break;
        }
        return $this->subject($subject)
        ->markdown($view, [
            'appointment' => $this->appointment,
            'recipientType' => $this->recipientType,
        ]);
        // return $this->subject($subject)
        // ->view($view)
        // ->with([
        //     'appointment' => $this->appointment,
        //                 'recipientType' => $this->recipientType,
        // ]);
    }
}