<?php

namespace App\Http;

use Capsule\Response;
use JsonSerializable;

class JsonResponse extends Response
{
	/**
	 * JsonResponse constructor.
	 * @param int $statusCode
	 * @param array<string,mixed>|JsonSerializable $data
	 * @param array<string,string> $headers
	 */
	public function __construct(int $statusCode, $data = [], array $headers = [])
	{
		parent::__construct(
			$statusCode,
			\json_encode($data, JSON_UNESCAPED_SLASHES),
			\array_merge(
				$headers,
				[
					"Content-Type" => "application/json"
				]
			)
		);
	}
}