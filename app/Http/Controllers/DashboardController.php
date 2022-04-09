<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = tap($request->user())->load(['slackToken', 'zoomToken']);

        return view('dashboard')
            ->with('user', $user)
            ->with('slackToken', $user->slackToken)
            ->with('zoomToken', $user->zoomToken);
    }
}
