<?php

namespace Tests;

use App\Models\Author;
use Capsule\ResponseStatus;
use Faker\Provider\Uuid;
use Tests\TestCase;

/**
 * @covers App\Http\Handlers\AuthorsHandler
 */
class AuthorsHandlerTest extends TestCase
{
	public function test_get_specific_author(): void
	{
		$author = new Author;
		$author->id = Uuid::uuid();
		$author->name = "Test Author";
		$author->website_url = "http://author.example.com";
		$author->save();

		$response = $this->makeRequest("get", "v1/authors/" . $author->id);

		$this->assertEquals(
			ResponseStatus::OK,
			$response->getStatusCode()
		);

		$payload = \json_decode($response->getBody());

		$this->assertEquals(
			$author->id,
			$payload->id
		);

		$this->assertEquals(
			$author->name,
			$payload->name
		);

		$this->assertEquals(
			$author->website_url,
			$payload->website_url
		);
	}

	public function test_get_all_authors(): void
	{
		$author = new Author;
		$author->id = Uuid::uuid();
		$author->name = "Test Author";
		$author->website_url = "http://author.example.com";
		$author->save();

		$response = $this->makeRequest("get", "v1/authors");

		$this->assertEquals(
			ResponseStatus::OK,
			$response->getStatusCode()
		);

		$payload = \json_decode($response->getBody());

		$this->assertIsArray($payload);
		$this->assertCount(1, $payload);

		$this->assertEquals(
			$author->id,
			$payload[0]->id
		);

		$this->assertEquals(
			$author->name,
			$payload[0]->name
		);

		$this->assertEquals(
			$author->website_url,
			$payload[0]->website_url
		);
	}
}