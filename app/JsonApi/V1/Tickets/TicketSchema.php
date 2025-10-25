<?php

namespace App\JsonApi\V1\Tickets;

use App\JsonApi\V1\Filters\WhereDate;
use App\JsonApi\V1\Filters\WhereDateFrom;
use App\JsonApi\V1\Filters\WhereDateTo;
use App\JsonApi\V1\Filters\WhereLike;
use App\Models\Ticket;
use Carbon\CarbonInterval;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
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
            Str::make('topic')->sortable(),
            Str::make('description')->sortable(),
            Str::make('user_id')->sortable(),
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

            // Кастомный поиск c
            WhereLike::make('searchNumber', 'number'),
            WhereLike::make('searchTopic', 'topic'),

            // Верхний поиск
            WhereLike::make('search', ['number', 'topic']),


            // Простой поиск
            Where::make('number'),
            Where::make('status_id'),
            Where::make('category_id'),
            Where::make('priority_id'),
            Where::make('technician_id'),
            Where::make('pharmacy_id'),

            // Фильтра по дате
            WhereDate::make('createdAt', 'created_at'),

            // по ренжу дат
            WhereDateFrom::make('createdAtFrom', 'created_at'),
            WhereDateTo::make('createdAtTo', 'created_at'),


            // пример поиска по аптеке GET /api/v1/tickets?filter[pharmacy][search]=Геленджик
            WhereHas::make($this, 'pharmacy'),
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
}
