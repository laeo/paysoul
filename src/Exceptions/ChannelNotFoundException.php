<?php

namespace Paysoul\Exceptions;

use Exception;

class ChannelNotFoundException extends Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct(
            sprintf('Channel "%s" cannot found.', $message),
            $code,
            $previous
        );
    }
}
