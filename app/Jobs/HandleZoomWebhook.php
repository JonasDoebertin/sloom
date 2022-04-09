<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\ZoomToken;
use App\Services\SlackStatusService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HandleZoomWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function middleware(): array
    {
        $key = implode('_', [
            Arr::get($this->data, 'event_ts'),
            Arr::get($this->data, 'payload.object.presence_status'),
            Arr::get($this->data, 'payload.object.id'),
        ]);

        return [(new WithoutOverlapping($key))->expireAfter(60)->dontRelease()];
    }

    public function handle(): void
    {
        $user = $this->getUser();

        if ($user === null || $user->slackToken === null) {
            return;
        }

        $slack = new SlackStatusService($user->slackToken);

        if (Str::lower($this->data['payload']['object']['presence_status']) === 'in_meeting') {
            $slack->setMeetingStatus();
        }
        else {
            $slack->resetMeetingStatus();
        }
    }

    protected function getUser(): ?User
    {
        $zoomToken = ZoomToken::query()
            ->where('resource_owner_id', Arr::get($this->data, 'payload.object.id'))
            ->with(['user', 'user.slackToken'])
            ->first();

        return $zoomToken?->user;
    }
}
