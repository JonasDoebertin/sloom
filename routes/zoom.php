<?php

use App\Http\Controllers\ZoomController;

Route::group(['prefix' => 'zoom'], function () {
    Route::get('connect', [ZoomController::class, 'connect'])->name('zoom.connect');
    Route::get('disconnect', [ZoomController::class, 'disconnect'])->name('zoom.disconnect');
    Route::get('callback', [ZoomController::class, 'callback'])->name('zoom.callback');
    Route::post('presence', [ZoomController::class, 'presence'])->name('zoom.presence');
});
