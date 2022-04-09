<?php

namespace App\OAuth\Slack;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class SlackResourceOwner implements ResourceOwnerInterface
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId(): ?string
    {
        return $this->response['user']['id'] ?? null;
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
