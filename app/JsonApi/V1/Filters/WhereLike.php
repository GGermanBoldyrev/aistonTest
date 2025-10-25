<?php

namespace App\JsonApi\V1\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use LaravelJsonApi\Core\Support\Arr;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;
use LaravelJsonApi\Eloquent\Filters\Concerns\IsSingular;

class WhereLike implements Filter
{
    use DeserializesValue;
    use IsSingular;

    private string $name;

    private mixed $columns;

    public static function make(string $name, $columns = null): self
    {
        return new static($name, $columns);
    }

    public function __construct(string $name, $columns = null)
    {
        $this->name = $name;
        $this->columns = $columns ?: Str::snake($name);
    }

    public function key(): string
    {
        return $this->name;
    }

    public function apply(Mixed $query, $value): Builder
    {
        $value = $this->deserialize($value);
        $columns = Arr::wrap($this->columns);

        return $query->where(function (Builder $q) use ($columns, $value) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', '%' . $value . '%');
            }
        });
    }
}
