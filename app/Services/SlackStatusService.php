<?php

namespace App\Services;

use App\DTOs\SlackStatus;
use App\Models\SlackToken;
use JoliCode\Slack\Client;
use JoliCode\Slack\ClientFactory;

class SlackStatusService
{
    protected SlackToken $token;

    protected Client $client;

    public function __construct(SlackToken $token)
    {
        $this->token = $token;
        $this->client = $this->getSlackClient();
    }

    protected function getSlackClient(): Client
    {
        return ClientFactory::create($this->token->access_token);
    }

    public function setMeetingStatus(): void
    {
        $currentStatus = $this->getCurrentStatus();

        if ($currentStatus->equals(SlackStatus::empty())) {
            $this->setNewStatus(SlackStatus::meeting());
        }
    }

    protected function getCurrentStatus(): SlackStatus
    {
        /** @var \JoliCode\Slack\Api\Model\ObjsUserProfile $profile */
        $profile = $this->client->usersProfileGet()->getProfile();

        return new SlackStatus(
            emoji: $profile->getStatusEmoji(),
            text: $profile->getStatusText(),
        );
    }

    protected function setNewStatus(SlackStatus $status)
    {
        $payload = [
            'status_emoji' => $status->emoji,
            'status_text'  => $status->text,
        ];

        dd(
            $this->client->usersProfileSet(
                [
                    'profile' => json_encode($payload, JSON_THROW_ON_ERROR),
                ]
            )
        );
    }

    public function resetMeetingStatus(): void
    {
        $currentStatus = $this->getCurrentStatus();

        if ($currentStatus->equals(SlackStatus::meeting())) {
            $this->setNewStatus(SlackStatus::empty());
        }
    }
}
