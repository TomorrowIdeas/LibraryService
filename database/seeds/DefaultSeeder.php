<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class DefaultSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
		$mock = Factory::create("en");

		$authors = [];

		for( $i = 0; $i < 250; $i++ ){
			$authors[] = [
				"id" => $mock->uuid,
				"name" => $mock->firstName() . " " . $mock->lastName,
				"created_at" => $mock->dateTime()->format("c")
			];
		}

		$this->table("authors")->insert($authors)->save();

		$publishers = [];

		for( $i = 0; $i < 50; $i++ ){
			$publishers[] = [
				"id" => $mock->uuid,
				"name" => $mock->company . " " . $mock->companySuffix,
				"created_at" => $mock->dateTime()->format("c")
			];
		}

		$this->table("publishers")->insert($publishers)->save();


		$books = [];

		for( $i = 0; $i < 1000; $i++ ){
			$books[] = [
				"id" => $mock->uuid,
				"isbn" => $mock->isbn13,
				"author_id" => $mock->randomElement($authors)["id"],
				"publisher_id" => $mock->randomElement($publishers)["id"],
				"title" => $mock->realText(64),
				"genre" => $mock->randomElement(["fiction", "non-fiction"]),
				"summary" => $mock->realText(1024),
				"pages" => $mock->numberBetween(5, 500),
				"edition" => $mock->numberBetween(1, 18),
				"preview_url" => $mock->url,
				"published_at" => $mock->date(),
				"created_at" => $mock->dateTime()->format("c")
			];
		}

		$this->table("books")->insert($books)->save();
    }
}
