<?php

namespace App\Queue;

use App\Core\Log;
use Exception;
use App\Queue\Message;

/**
 *
 * Advanced message deserializer specific to the NotificationService domain.
 *
 * 1) Attempt standard message deserialization via the EventBus\Message::deserialize()
 * 2) If default deserialization fails, fall back to an AWS sent message.
 *
 */
final class MessageDeserializer
{
	/**
	 * Deserialize an incoming message.
	 *
	 * @param string $serializedMessage
	 * @return Message|null
	 */
	public function deserialize(string $serializedMessage): ?Message
	{
		// Try deserializing as an internal message first.
		try {

			$message = Message::deserialize($serializedMessage);

		}
		catch( Exception $exception ){

			// Try deserializing as an AWS message.
			$message = $this->deserializeAwsMessage(
				\json_decode($serializedMessage)
			);

		}

		if( empty($message) ){
			Log::warning("[MESSAGE] Unknown message format", ["message" => $serializedMessage]);
			return null;
		}

		Log::info("[MESSAGE] {$message->getName()}", ["version" => $message->getVersion(), "id" => $message->getId(), "user_id" => $message->getUserId(), "origin" => $message->getOrigin(), "name" => $message->getName(), "tags" => $message->getTags(), "attributes" => $message->getAttributes(), "sent_at" => $message->getSentAt()->format("c")]);
		return $message;
	}

	/**
	 * This deserializes a Message that was generated within AWS.
	 *
	 * @param object $deserializedMessage
	 * @return Message|null
	 */
	private function deserializeAwsMessage(object $deserializedMessage): ?Message
	{
		if( \property_exists($deserializedMessage, "TopicArn") ){

			$snsMessage = \json_decode($deserializedMessage->Message);

			return new Message(
				$deserializedMessage->MessageId,
				null,
				$deserializedMessage->TopicArn,
				$snsMessage->notificationType . "Notification",
				(array) $snsMessage
			);
		}

		return null;
	}
}