<?php

namespace App\Http\Controllers;

use App\Jobs\HandleZoomWebhook;
use App\Models\ZoomToken;
use App\OAuth\Zoom\ZoomProvider;
use Arr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
    private ZoomProvider $zoomProvider;

    public function __construct(ZoomProvider $zoomProvider)
    {
        $this->zoomProvider = $zoomProvider;
    }

    public function connect(Request $request): RedirectResponse
    {
        $authorizationUrl = $this->zoomProvider->getAuthorizationUrl();

        return redirect()->to($authorizationUrl);
    }

    public function callback(Request $request): RedirectResponse
    {
        $token = $this->zoomProvider->getAccessToken('authorization_code', [
            'code' => $request->get('code'),
        ]);

        $resourceOwner = $this->zoomProvider->getResourceOwner($token);

        ZoomToken::fromAccessToken($token, $resourceOwner, $request->user());

        return redirect()->route('dashboard');
    }

    public function disconnect(Request $request): RedirectResponse
    {
        $request->user()->zoomToken()?->delete();

        return redirect()->route('dashboard');
    }

    public function presence(Request $request)
    {
        $data = $request->json()?->all() ?? [];

        if (Arr::get($data, 'event') === 'user.presence_status_updated') {
            $this->dispatch(new HandleZoomWebhook($data));
        }

        return response('OK', 204);
    }
}
