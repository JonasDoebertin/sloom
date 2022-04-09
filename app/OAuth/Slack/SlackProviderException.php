<?php

namespace App\OAuth\Slack;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class SlackProviderException extends IdentityProviderException
{
    public static function fromResponse(ResponseInterface $response, $message = null): self
    {
        return new static($message, $response->getStatusCode(), (string) $response->getBody());
    }
}
