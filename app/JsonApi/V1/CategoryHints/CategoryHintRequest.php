<?php

namespace App\JsonApi\V1\CategoryHints;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class CategoryHintRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'min:5'],
            'hint_type' => ['required', Rule::in(['positive', 'negative'])],
            'order' => ['sometimes', 'integer', 'min:0'],
            'category' => ['required', JsonApiRule::toOne()],
        ];
    }
}
