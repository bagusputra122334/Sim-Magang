<?php

namespace App\Exceptions;

use Exception;

class PositionDeleteRestrictedException extends Exception
{
    public function __construct(string $message = 'Posisi tidak dapat dihapus karena berelasi dengan data pendaftaran.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
