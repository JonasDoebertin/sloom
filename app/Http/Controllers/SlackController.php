<?php

namespace App\Http\Controllers;

use App\Models\SlackToken;
use App\Models\ZoomToken;
use App\OAuth\Slack\SlackProvider;
use App\OAuth\Zoom\ZoomProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SlackController extends Controller
{
    private SlackProvider $slackProvider;

    public function __construct(SlackProvider $slackProvider)
    {
        $this->slackProvider = $slackProvider;
    }

    public function connect(Request $request): RedirectResponse
    {
        $authorizationUrl = $this->slackProvider->getAuthorizationUrl();

        return redirect()->to($authorizationUrl);
    }

    public function callback(Request $request): RedirectResponse
    {
        $token = $this->slackProvider->getAccessToken('authorization_code', [
            'code' => $request->get('code')
        ]);

        SlackToken::fromAccessToken($token, $request->user());

        return redirect()->route('dashboard');
    }

    public function disconnect(Request $request): RedirectResponse
    {
        $request->user()->slackToken()?->delete();

        return redirect()->route('dashboard');
    }
}
