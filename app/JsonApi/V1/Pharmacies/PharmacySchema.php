<?php

namespace App\JsonApi\V1\Pharmacies;

use App\JsonApi\V1\Filters\WhereLike;
use App\Models\Pharmacy;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class PharmacySchema extends Schema
{

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Pharmacy::class;

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('code')->sortable(),
            Str::make('address')->sortable(),
            Str::make('city')->sortable(),

            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),

            // relations
            HasMany::make('tickets')->type('tickets'),
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
            // Точный поиск
            WhereIdIn::make($this),
            Where::make('code'),
            Where::make('city'),
            Where::make('address'),

            // Кастомный поиск
            WhereLike::make('searchCode', 'code'),
            WhereLike::make('searchAddress', 'address'),
            WhereLike::make('searchCity', 'city'),
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
