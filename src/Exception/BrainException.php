<?php

namespace Brain\Exception;

use Exception;

class BrainException extends Exception
{
    public function __construct(string $msg, int $code = 404)
    {
        parent::__construct($msg, $code);
        echo view('/tutoriel/show/8');
    }

}