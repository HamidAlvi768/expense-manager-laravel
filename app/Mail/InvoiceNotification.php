<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $invoicePayment;
    public $recipientType; // 'patient' or 'admin'

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice, $invoicePayment, $recipientType)
    {
        $this->invoice = $invoice;
        $this->invoicePayment = $invoicePayment;
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
                    ->markdown('emails.invoice')
                    ->with([
                        'userName' => $this->invoice->user->name,
                        'invoiceNumber' => $this->invoice->invoice_number,
                        'treatmentPlanNumber' => $this->invoice->patienttreatmentplan->treatment_plan_number ?? "TPL",
                        'doctorName' => $this->invoice->doctor->name,
                        'grandTotal' => $this->invoice->grand_total,
                        'invoiceDate' => $this->invoice->invoice_date,
                        'invoiceItems' => $this->invoice->invoiceItems->map(function ($item) {
                            return [
                                'title' => $item->patienttreatmentplanprocedures->procedure->title ?? $item->title,
                                'price' => $item->total,
                            ];
                        }),
                        'paidAmount' => $this->invoicePayment->paid_amount,
                        'dueAmount' => $this->invoice->due,
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
            ? "A new Invoice Added to System"
            : "Invoice Generated Successfully - Invoice Number: " . $this->invoice->invoice_number;
    }
}