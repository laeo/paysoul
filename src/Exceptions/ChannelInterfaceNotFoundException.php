<?php

namespace Paysoul\Exceptions;

use Exception;

class ChannelInterfaceNotFoundException extends Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct(
            sprintf('Channel interface "%s" cannot found.', $message),
            $code,
            $previous
        );
    }
}
