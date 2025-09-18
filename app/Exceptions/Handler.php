<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExceptionOccurred;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */

     public function render($request, Throwable $exception)
     {
         if ($exception instanceof TooManyRequestsHttpException) {
             return back()->withErrors(['error' => 'You are making too many requests. Please try again later.']);
         }
     
         return parent::render($request, $exception);
     }
     
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // $this->sendExceptionEmail($e);
        });
    }


    protected function sendExceptionEmail(Throwable $exception)
    {
        try {
            // Define the administrator's email
            $adminEmail = config('mail.admin_email', 'admin@example.com');

            // Send the exception details to the admin
            Mail::to($adminEmail)->send(new ExceptionOccurred($exception));
        } catch (Throwable $mailException) {
            // Log the email sending failure to avoid a feedback loop
            logger()->error('Failed to send exception email: ' . $mailException->getMessage());
        }
    }
}
