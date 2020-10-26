<?php

namespace App\Scheduler\Handlers;

use App\Core\Log;
use Capsule\Request;
use Psr\Http\Client\ClientInterface;

class SampleHandler
{
	public function monthlyReport(
		ClientInterface $httpClient
	): void
	{
		$response = $httpClient->sendRequest(
			new Request("get", "https://tomorrow.me")
		);

		Log::info("Response: " . $response->getStatusCode() . " " . $response->getReasonPhrase());
	}
}