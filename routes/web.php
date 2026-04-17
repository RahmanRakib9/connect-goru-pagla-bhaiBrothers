<?php

use Illuminate\Support\Facades\Route;

Route::get(
    "/welcome",
    [App\Http\Controllers\welcomeController::class, 'welcome']
);