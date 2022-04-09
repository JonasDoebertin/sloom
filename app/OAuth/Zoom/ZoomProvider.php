<?php

namespace App\OAuth\Zoom;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class ZoomProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl(): string
    {
        return 'https://zoom.us/oauth/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://zoom.us/oauth/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://api.zoom.us/v2/users/me';
    }

    protected function getDefaultScopes()
    {
        return ['user:read', 'user:write'];
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() < 200 && $response->getStatusCode() >= 300) {
            throw ZoomProviderException::fromResponse($response, $data['message'] ?? null);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): ZoomResourceOwner
    {
        return new ZoomResourceOwner($response);
    }
}
