<?php

namespace App\Utils\ModelFilter\Exceptions;

use Exception;

class InvalidFilterException extends Exception
{
    /**
     * @param string $message The exception message.
     * @param int $code The exception code.
     * @param Exception|null $previous The previous exception.
     */
    public function __construct(
        $message = 'Invalid filter configuration',
        $code = 0,
        Exception $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
