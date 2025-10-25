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
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

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
    ->resources(function (ResourceRegistrar $server) {

        $lookupTicketResources = [
            'priorities' => PriorityController::class,
            'categories' => CategoryController::class,
            'statuses' => StatusController::class,
            'technicians' => TechnicianController::class,
            'pharmacies' => PharmacyController::class,
        ];

        foreach ($lookupTicketResources as $resourceName => $controller) {
            $server->resource($resourceName, $controller)
                ->relationships(function (Relationships $relations) use ($resourceName) {
                    $relations->hasMany('tickets');

                    if ($resourceName === 'categories') {
                        $relations->hasMany('hints');
                    }
                });
        }

        $server->resource('tickets', TicketController::class)
            ->relationships(function (Relationships $relations) {
                $relations->hasOne('pharmacy');
                $relations->hasOne('priority');
                $relations->hasOne('status');
                $relations->hasOne('category');
                $relations->hasOne('technician');
                $relations->hasMany('attachments');
            });

        $server->resource('category-hints', CategoryHintController::class);
    });
