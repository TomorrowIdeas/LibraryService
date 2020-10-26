<?php

namespace App\Queue;

use DateTime;
use RuntimeException;

final class Message
{
	/**
	 * Message version.
	 *
	 * @var string
	 */
	private $version = "1";

	/**
	 * Message ID.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * User ID.
	 *
	 * @var string|null
	 */
	protected $user_id;

	/**
	 * Message name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Message data.
	 *
	 * @var mixed
	 */
	protected $data;

	/**
	 * Message tags.
	 *
	 * @var array<string>
	 */
	protected $tags = [];

	/**
	 * Message attributes.
	 *
	 * @var array<string, string>
	 */
	protected $attributes = [];

	/**
	 * Message origin hostname.
	 *
	 * @var string
	 */
	protected $origin;

	/**
	 * Message sent at timestamp.
	 *
	 * @var DateTime
	 */
	protected $sent_at;

	/**
	 * Message constructor.
	 *
	 * @param string $id
	 * @param string|null $user_id
	 * @param string $origin
	 * @param string $name
	 * @param array|null $data
	 * @param array|null $tags
	 * @param array|null $attributes
	 * @param DateTime|null $sent_at
	 */
	public function __construct(string $id, ?string $user_id, string $origin, string $name, ?array $data = null, ?array $tags = null, ?array $attributes = null, ?DateTime $sent_at = null)
	{
		$this->id = $id;
		$this->user_id = $user_id;
		$this->origin = $origin;
		$this->name = $name;
		$this->data = $data ?? [];
		$this->tags = $tags ?? [];
		$this->attributes = $attributes ?? [];
		$this->sent_at = $sent_at ?? new DateTime;
	}

	/**
	 * Make a Message instance from a serialized string.
	 *
	 * @param string $serializedMessage
	 * @throws RuntimeException
	 * @return Message
	 */
	public static function deserialize(string $serializedMessage): Message
	{
		$jsonObject = \json_decode($serializedMessage);

		if( \json_last_error() !== JSON_ERROR_NONE ){
			throw new RuntimeException("Serialized message is not in JSON format.");
		}

		// Unwrap messages forwarded by SNS
		if( \property_exists($jsonObject, "Type") &&
			$jsonObject->Type === "Notification" &&
			\property_exists($jsonObject, "Message") ){

			return self::deserialize($jsonObject->Message);
		}

		// Validate required Message
		if( !self::isValidMessage($jsonObject) ){
			throw new RuntimeException("Invalid message.");
		}

		$message = new static(
			$jsonObject->id,
			$jsonObject->user_id ?? null,
			$jsonObject->origin,
			$jsonObject->name,
			(array) ($jsonObject->data ?? []),
			(array) ($jsonObject->tags ?? []),
			(array) ($jsonObject->attributes ?? []),
			new DateTime($jsonObject->sent_at)
		);

		if( $message->version !== $jsonObject->version ){
			throw new RuntimeException("Message version mismatch.");
		}

		return $message;
	}

	/**
	 * Validate that all required components of Message are there.
	 *
	 * @param object $message
	 * @return boolean
	 */
	protected static function isValidMessage(object $message): bool
	{
		if( !isset($message->version) ){
			return false;
		}

		if( !isset($message->id) ){
			return false;
		}

		if( !isset($message->name) ){
			return false;
		}

		if( !isset($message->origin) ){
			return false;
		}

		if( !isset($message->sent_at) ||
			DateTime::createFromFormat(DateTime::ISO8601, $message->sent_at) === false ){
			return false;
		}

		return true;
	}

	/**
	 * Get the Message version.
	 *
	 * @return string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	/**
	 * Get the Message ID.
	 *
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * Get the User ID.
	 *
	 * @return string|null
	 */
	public function getUserId(): ?string
	{
		return $this->user_id;
	}

	/**
	 * Set the name on the Message instance.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * Get the Message name.
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Add data to Message instance.
	 *
	 * @param array $data
	 * @return void
	 */
	public function setData(array $data): void
	{
		$this->data = $data;
	}

	/**
	 * Get the Message data/payload.
	 *
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Does payload data have property.
	 *
	 * @param string $property
	 * @return boolean
	 */
	public function hasProperty(string $property): bool
	{
		return ($this->data && \property_exists((object) $this->data, $property));
	}

	/**
	 * Get a property from the data payload.
	 *
	 * @param string $property
	 * @return mixed
	 */
	public function getProperty(string $property)
	{
		if( $this->hasProperty($property) ){
			return ((object) $this->data)->{$property};
		}

		return null;
	}

	/**
	 * Get a property from the data payload.
	 *
	 * @param string $property
	 * @return mixed
	 */
	public function __get(string $property)
	{
		return $this->getProperty($property);
	}

	/**
	 * Add a tag to the Message instance.
	 *
	 * @param string $tag
	 * @return void
	 */
	public function withTag(string $tag): void
	{
		$this->tags[] = $tag;
	}

	/**
	 * Message has given tag.
	 *
	 * @param string $tag
	 * @return boolean
	 */
	public function hasTag(string $tag): bool
	{
		return \in_array($tag, $this->tags);
	}

	/**
	 * Get all Message tags.
	 *
	 * @return array
	 */
	public function getTags(): array
	{
		return $this->tags;
	}

	/**
	 * Add attribute to Message instance.
	 *
	 * @param string $name
	 * @param string $value
	 * @return void
	 */
	public function withAttribute(string $name, string $value): void
	{
		$this->attributes[$name] = $value;
	}

	/**
	 * Message has given attribute.
	 *
	 * @param string $attribute
	 * @return boolean
	 */
	public function hasAttribute(string $attribute): bool
	{
		return \array_key_exists($attribute, $this->attributes);
	}

	/**
	 * Get given Message attribute.
	 *
	 * @param string $attribute
	 * @return string|null
	 */
	public function getAttribute(string $attribute): ?string
	{
		return $this->attributes[$attribute] ?? null;
	}

	/**
	 * Get all Message attributes.
	 *
	 * @return array
	 */
	public function getAttributes(): array
	{
		return $this->attributes;
	}

	/**
	 * Get the Message origin.
	 *
	 * Typically the producer"s hostname - but could be any other unique producer identifier.
	 *
	 * @return string
	 */
	public function getOrigin(): string
	{
		return $this->origin;
	}

	/**
	 * Get the Message sent at timestamp.
	 *
	 * @return DateTime
	 */
	public function getSentAt(): DateTime
	{
		return $this->sent_at;
	}

	/**
	 * Get the Message as an array.
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			"version" => $this->version,
			"id" => $this->id,
			"user_id" => $this->user_id,
			"name" => $this->name,
			"data" => (object) $this->data,
			"tags" => $this->tags,
			"attributes" => (object) $this->attributes,
			"origin" => $this->origin,
			"sent_at" => $this->sent_at->format("c")
		];
	}

	/**
	 * Serialize the Message for sending.
	 *
	 * @return string
	 */
	public function serialize(): string
	{
		return \json_encode($this->toArray());
	}
}