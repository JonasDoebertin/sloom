<?php

namespace App\OAuth\Slack;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class SlackProvider extends AbstractProvider
{
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://slack.com/oauth/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://slack.com/api/oauth.access';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://slack.com/api/users.identity';
    }

    protected function getDefaultScopes()
    {
        return ['users.profile:read', 'users.profile:write'];
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (!isset($data['ok']) || $data['ok'] !== true) {
            throw SlackProviderException::fromResponse($response, $data['error'] ?? null);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): SlackResourceOwner
    {
        return new SlackResourceOwner($response);
    }
}
