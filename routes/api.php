<?php

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
    ]);
});

// JsonApiRoute
JsonApiRoute::server('v1')
    ->prefix('v1')
    ->namespace('App\Http\Controllers\Api\V1')
    ->resources(function ($server) {

    });
