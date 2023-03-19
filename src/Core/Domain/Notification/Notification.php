<?php

namespace Core\Domain\Notification;

class Notification
{

    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function addError(array $error): void
    {
        $this->errors[] = $error;
    }
}
