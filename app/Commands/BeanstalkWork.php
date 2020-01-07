<?php

namespace App\Commands;

use App\Services\BeanStalkWorkerService;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class BeanstalkWork extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'beanstalk:work {tubeName}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'This command starts and stops the entire queue! Be careful!';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(BeanStalkWorkerService $worker)
    {
        $tube = $this->argument('tubeName');

        while (true) {

            //Check to see if the current jobs is set, and has a value
            if ($worker->consumer->countJobs($tube) >= 1) {
                $job = $worker->consumer->watchTube($tube)->reserve();

                echo "JobID: {$job->id} \n";
                echo "Job Payload \n";
                echo json_encode($job->payload);
                echo "\n";
                echo "Deleting Job.. \n";
                $job->delete();
            } else {
                echo 'No Jobs!';
                sleep(5);
                echo "\n";
            }
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
