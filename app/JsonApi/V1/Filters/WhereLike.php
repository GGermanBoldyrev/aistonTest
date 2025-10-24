<?php

namespace App\JsonApi\V1\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;
use LaravelJsonApi\Eloquent\Filters\Concerns\IsSingular;

class WhereLike implements Filter
{
    use DeserializesValue;
    use IsSingular;

    private string $name;

    private string $column;

    public static function make(string $name, string $column = null): self
    {
        return new static($name, $column);
    }

    public function __construct(string $name, string $column = null)
    {
        $this->name = $name;
        $this->column = $column ?: Str::snake($name);
    }

    public function key(): string
    {
        return $this->name;
    }

    public function apply(Mixed $query, $value): Builder
    {
        return $query->where($this->column, 'like', '%' . $value . '%');
    }
}
