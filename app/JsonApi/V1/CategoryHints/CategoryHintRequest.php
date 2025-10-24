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
        if ($this->isCreating()) {
            return [
                'text' => ['required', 'string', 'min:5'],
                'type' => ['required', Rule::in(['positive', 'negative'])],

                'category' => ['required', JsonApiRule::toOne()],
            ];
        }

        return [
            'text' => ['sometimes', 'string', 'min:5'],
            'type' => ['sometimes', Rule::in(['positive', 'negative'])],
            'category' => ['sometimes', JsonApiRule::toOne()],
        ];
    }

}
