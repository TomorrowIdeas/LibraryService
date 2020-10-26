<?php

namespace App\Scheduler\Providers;

use Carton\Container;
use Carton\ServiceProviderInterface;
use GO\Scheduler;

class ScheduleProvider implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->singleton(
			Scheduler::class,
			function(Container $container): Scheduler {

				$scheduler = new Scheduler;

				foreach( \config("scheduler.schedules") ?? [] as $schedule => $handlers ){
					foreach( $handlers as $handler ){

						$callable = $container->makeCallable($handler);

						$scheduler->call(
							$callable,
							$container->getCallableArguments($callable),
							\md5($handler)
						)->at($schedule);
					}
				}

				return $scheduler;
			}
		);
	}
}