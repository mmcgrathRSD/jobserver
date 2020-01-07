<?php

namespace App\Providers;

use App\Services\BeanStalkWorkerService;
use App\Services\QueueTaskService;
use Illuminate\Support\ServiceProvider;
use Popcorn\Beans\Consumer;
use Popcorn\Beans\Models\QueueTask;
use Popcorn\Beans\Producer;
use xobotyi\beansclient\Connection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerQueueTaskService();
        $this->registerBeanstalkWorkerService();
    }

    public function registerQueueTaskService()
    {
        $this->app->bind(QueueTaskService::class, function () {
            return new QueueTaskService(
                new QueueTask([
                    'host'      => getenv('MONGOHOST'),
                    'database'  => getenv('MONGODATABASE'),
                    'username'  => getenv('MONGOUSER'),
                    'password'  => getenv('MONGOPASSWORD'),
                ]),
                new Producer(
                    new Connection('127.0.0.1', 11300, 2, true)
                ),
            );
        });
    }

    public function registerBeanstalkWorkerService()
    {
        $this->app->bind(BeanStalkWorkerService::class, function () {
            return new BeanStalkWorkerService(
                new Consumer(
                    new Connection('127.0.0.1', 11300, 2, true)
                )
            );
        });
    }
}
