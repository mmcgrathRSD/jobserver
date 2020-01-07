<?php

namespace App\Services;

class UserService
{
    public $user;
    public $producer;

    public function __construct($user, $producer)
    {
        $this->user     = $user;
        $this->producer = $producer;
    }
}
