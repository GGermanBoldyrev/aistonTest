<?php

namespace App\JsonApi\V1\Tickets;

use App\Models\Ticket;
use Carbon\CarbonInterval;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class TicketSchema extends Schema
{

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Ticket::class;

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('number')->sortable(),
            Str::make('topic'),
            Str::make('description'),
            Boolean::make('isWarrantyCase'),

            // Тут пусть фронт динамически считает сколько времени прошло
            DateTime::make('reactedAt')->sortable()->readOnly(),
            DateTime::make('resolvedAt')->sortable()->readOnly(),

            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),

            // relations
            BelongsTo::make('pharmacy')->type('pharmacies'),
            BelongsTo::make('priority')->type('priorities'),
            BelongsTo::make('category')->type('categories'),
            BelongsTo::make('technician')->type('technicians'),
            BelongsTo::make('status')->type('statuses'),
        ];
    }

    /**
     * Get the resource filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),

            // Поиск по номеру
            Where::make('number'),

            // Поиск по статусу
            Where::make('status_id'),
        ];
    }

    /**
     * Get the resource paginator.
     *
     * @return Paginator|null
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }

    public function authorizable(): bool
    {
        return false;
    }

}
