<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\PriorityController;
use App\Http\Controllers\Api\V1\StatusController;
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
    ->resources(function ($server) {
        $server->resource('priorities', PriorityController::class);
        $server->resource('categories', CategoryController::class);
        $server->resource('statuses', StatusController::class);
    });
