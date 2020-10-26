<?php

return [

	/**
	 * Scheduler specific providers.
	 */
	"providers" => [
		App\Scheduler\Providers\ScheduleProvider::class
	],

	/**
	 * Schedules to run.
	 *
	 * Scheduler can also support CRON syntax. For example:
	 *
	 * "30 1 1 * *" => [
	 * 		"App\Scheduler\Handlers\ReportsHandler@monthlyReport"
	 * ]
	 *
	 * The above example would run at 1:30 AM on the first day of each month.
	 *
	 */
	"schedules" => [

		"@hourly" => [],

		"@daily" => [],

		"@weekly" => [],

		"@monthly" => [
			"App\\Scheduler\\Handlers\\SampleHandler@monthlyReport"
		],

		"@yearly" => []
	]
];