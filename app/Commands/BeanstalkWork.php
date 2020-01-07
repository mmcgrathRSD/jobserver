<?php

namespace App\Commands;

use ReflectionClass;
use App\Services\BeanStalkWorkerService;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use ReflectionMethod;

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

                $this->info("Starting Job {$job->id}, Job is: {$job->payload['title']}, UserID: {$job->payload['user_id']}");

                //Split the queue task title into class and method
                $makeFunctionAndMethod = explode("::", $job->payload['title']);

                $class = "App{$makeFunctionAndMethod[0]}";
                $taskClassMethod = new ReflectionMethod(new $class, $makeFunctionAndMethod[1]);
                $taskClassMethod->invokeArgs(new $class, [$job->payload['user_id']]);

                // call_user_func_array(
                //     [
                //         "App{$makeFunctionAndMethod[0]}",
                //         $makeFunctionAndMethod[1]
                //     ],
                //     [
                //         $job->payload['user_id']
                //     ]
                // );

                // echo "Job Payload \n";
                // print_r($job->payload);
                // echo json_encode($job->payload);
                // echo "\n";
                // echo "Deleting Job.. \n";
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
