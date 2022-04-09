<?php

namespace App\OAuth\Zoom;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ZoomResourceOwner implements ResourceOwnerInterface
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId(): ?string
    {
        return $this->response['id'] ?? null;
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
