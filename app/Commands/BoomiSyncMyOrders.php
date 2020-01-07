<?php

namespace App\Commands;

use Popcorn\Beans\Payload;
use App\Services\QueueTaskService;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class BoomiSyncMyOrders extends Command
{
    /**
     * The batch this command is responsible for writing to queue
     *
     * @var string
     */
    protected $batch;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'algolia:boomi-sync-my-orders';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Accepts a user id, and will sync orders for a specific customer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(QueueTaskService $queueTaskService)
    {
        // $this->warn("User Id Is: {$userId}");
        $tasks = $queueTaskService->queueTask->find(['batch' => $this->batch]);

        foreach ($tasks as $task) {
            $queueTaskService->producer->useTube('myTube')->put($task);
        }
    }
    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
