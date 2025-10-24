<?php

namespace App\JsonApi\V1\Statuses;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class StatusRequest extends ResourceRequest
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
                Rule::unique('statuses', 'name')->ignore($this->model?->id),
            ],
            'color' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

}
