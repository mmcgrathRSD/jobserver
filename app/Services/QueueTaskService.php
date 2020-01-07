<?php

namespace App\Services;

use Popcorn\Beans\Models\QueueTask;

class QueueTaskService
{
    public $queueTask;
    public $producer;

    public function __construct($queueTask, $producer)
    {
        $this->queueTask = $queueTask;
        $this->producer = $producer;
    }
}
