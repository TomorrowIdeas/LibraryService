<?php

namespace App\Http;

use JsonSerializable;
use Remodel\Resource\Resource;

class JsonSerializer implements JsonSerializable
{
    /**
     * Resource to serialize.
     *
     * @var Resource
     */
    protected $resource;

    /**
     * JsonSerializer constructor.
     *
     * @param Resource $resource
     */
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Serialize the response body.
     *
     * @throws \Exception
     * @return string
     */
    public function serialize(): string
    {
        if( ($serialized = \json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES)) === false ){
            throw new \Exception("Cannot serialize response.");
        }

        return $serialized;
    }

    /**
     * Satisfies JsonSerializable interface contract.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
		return $this->resource->toData();
    }
}