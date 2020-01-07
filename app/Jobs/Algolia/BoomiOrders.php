<?php

namespace App\Jobs\Algolia;

use App\Services\UserService;
use Popcorn\Beans\Models\User;

class BoomiOrders
{

    public function syncMyOrders($user_id)
    {

        $user = new User([
            'host'      => getenv('MONGOHOST'),
            'database'  => getenv('MONGODATABASE'),
            'username'  => getenv('MONGOUSER'),
            'password'  => getenv('MONGOPASSWORD'),
        ]);

        //$userFound = $user->find(['_id' => new \MongoDB\BSON\ObjectId("$user_id")]);

        $userFound = $user->findOne([
            '_id' =>  new \MongoDB\BSON\ObjectId($user_id),
        ]);

        var_dump($userFound);
    }
}
