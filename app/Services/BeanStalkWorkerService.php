<?php

namespace App\Services;

use Popcorn\Beans\Consumer;

class BeanStalkWorkerService
{
    public $consumer;

    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }
}
