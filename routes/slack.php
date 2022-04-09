<?php

use App\Http\Controllers\SlackController;

Route::group(['prefix' => 'slack'], function () {
    Route::get('connect', [SlackController::class, 'connect'])->name('slack.connect');
    Route::get('disconnect', [SlackController::class, 'disconnect'])->name('slack.disconnect');
    Route::get('callback', [SlackController::class, 'callback'])->name('slack.callback');
});
