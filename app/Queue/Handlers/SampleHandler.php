<?php

namespace App\Queue\Handlers;

use App\Core\Log;
use Capsule\Request;
use Psr\Http\Client\ClientInterface;
use Syndicate\Message;

class SampleHandler
{
	public function onCreated(
		Message $message,
		ClientInterface $httpClient): void
	{
		$payload = $message->getPayload();

		$response = $httpClient->sendRequest(
			new Request("get", "https://api.tomorrow.me/v1/people/" . $payload->id)
		);

		Log::info("Response is: " . $response->getStatusCode() . " " . $response->getReasonPhrase());
		$message->delete();
	}
}