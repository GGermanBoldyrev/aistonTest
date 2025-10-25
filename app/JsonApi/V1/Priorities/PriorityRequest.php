<?php

namespace App\JsonApi\V1\Priorities;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class PriorityRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('priorities', 'name')->ignore($this->model?->id),
            ],
            'color' => [
                'nullable',
                'string',
                'max:255',
            ],
            'order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
