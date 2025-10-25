<?php

use App\Http\Controllers\Api\V1\AttachmentController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CategoryHintController;
use App\Http\Controllers\Api\V1\PharmacyController;
use App\Http\Controllers\Api\V1\PriorityController;
use App\Http\Controllers\Api\V1\StatusController;
use App\Http\Controllers\Api\V1\TechnicianController;
use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
    ]);
});

Route::middleware(\App\Http\Middleware\DevAuth::class)
    ->prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::post('/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
        Route::delete('/attachments/{uuid}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
    });

// JsonApiRoute
JsonApiRoute::server('v1')
    ->prefix('v1')
    ->middleware(\App\Http\Middleware\DevAuth::class) // для логина в локалке
    ->resources(function ($server) {
        $server->resource('priorities', PriorityController::class);
        $server->resource('categories', CategoryController::class);
        $server->resource('statuses', StatusController::class);
        $server->resource('technicians', TechnicianController::class);
        $server->resource('pharmacies', PharmacyController::class);
        $server->resource('tickets', TicketController::class);
        $server->resource('category-hints', CategoryHintController::class);
    });
