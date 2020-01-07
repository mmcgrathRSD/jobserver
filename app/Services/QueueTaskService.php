<?php

namespace App\Services;

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
