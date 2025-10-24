<?php

namespace App\JsonApi\V1\Tickets;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class TicketRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tickets', 'number')->ignore($this->model?->id),
            ],
            'topic' => ['required', 'string', 'max:255'],

            'pharmacy' => ['nullable'],
            'priority' => ['nullable'],
            'category' => ['nullable'],
            'technician' => ['nullable'],
            'status' => ['nullable'],
        ];
    }

}
