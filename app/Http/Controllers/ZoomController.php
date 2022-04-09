<?php

namespace App\Http\Controllers;

use App\Jobs\HandleZoomWebhook;
use App\Models\ZoomToken;
use App\OAuth\Zoom\ZoomProvider;
use Arr;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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

        // fopdl2satf2rnwroxtitow
        // fOpDl2saTF2rNwRoXtITow
    }

    public function callback(Request $request): RedirectResponse
    {
        $token = $this->zoomProvider->getAccessToken('authorization_code', [
            'code' => $request->get('code')
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
        ray($data);

        if (Arr::get($data, 'event') === 'user.presence_status_updated') {
            ray('dispatch');
            $this->dispatch(new HandleZoomWebhook($data));
        }

        return response('OK', 204);
    }
}
