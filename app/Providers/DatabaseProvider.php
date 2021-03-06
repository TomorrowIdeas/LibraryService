<?php


namespace App\Providers;

use Carton\Container;
use Carton\ServiceProviderInterface;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\DB;

class DatabaseProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
		$manager = new Manager;

        foreach( \config("database.connections") as $name => $connection ){
            $manager->addConnection($connection, $name);
        }

        $manager->setAsGlobal();
        $manager->bootEloquent();

        $container->set(DB::class, $manager);
    }
}