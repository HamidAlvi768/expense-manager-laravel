<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ExceptionOccurred extends Mailable
{
    use SerializesModels;

    public $exception;
    public $exceptionType;
    public $parsedTrace;

    /**
     * Create a new message instance.
     *
     * @param \Throwable $exception
     */
    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;

        // Determine the exception type (e.g., DatabaseException, QueryException, etc.)
        $this->exceptionType = get_class($exception);

        // Parse the trace into a simplified format
        $this->parsedTrace = $this->getParsedTrace($exception);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "{$this->exceptionType}";

        return $this->subject($subject)
                    ->markdown('emails.exception_occurred')
                    ->with([
                        'exception' => $this->exception,
                        'exceptionType' => $this->exceptionType,
                        'parsedTrace' => $this->parsedTrace,
                    ]);
    }

    /**
     * Parse the exception trace into a simplified format.
     *
     * @param \Throwable $exception
     * @return array
     */
    private function getParsedTrace(Throwable $exception)
    {
        $trace = $exception->getTrace();
        $parsedTrace = [];

        foreach ($trace as $traceEntry) {
            if (isset($traceEntry['file'], $traceEntry['line'])) {
                $parsedTrace[] = [
                    'file' => $traceEntry['file'],
                    'line' => $traceEntry['line'],
                ];
            }
        }

        return $parsedTrace;
    }
}
