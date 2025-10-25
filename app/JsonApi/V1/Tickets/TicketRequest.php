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
        $uniqueNumber = Rule::unique('tickets', 'number')->ignore($this->model()?->id);

        if ($this->isCreating()) {
            return [
                'number' => ['required', 'string', 'max:255', $uniqueNumber],
                'topic' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'min:10'],
                'user_id' => ['required', 'string', 'min:0'],
                'isWarrantyCase' => ['sometimes', 'boolean'],

                'pharmacy' => ['required', JsonApiRule::toOne()],
                'priority' => ['required', JsonApiRule::toOne()],
                'category' => ['required', JsonApiRule::toOne()],

                'status' => ['sometimes', JsonApiRule::toOne()],
                'technician' => ['sometimes', 'nullable', JsonApiRule::toOne()],
            ];
        }

        return [
            'number' => ['sometimes', 'required', 'string', 'max:255', $uniqueNumber],
            'topic' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'min:10'],
            'user_id' => ['sometimes', 'required', 'string', 'min:0'],
            'isWarrantyCase' => ['sometimes', 'boolean'],

            'pharmacy' => ['sometimes', JsonApiRule::toOne()],
            'priority' => ['sometimes', JsonApiRule::toOne()],
            'category' => ['sometimes', JsonApiRule::toOne()],
            'status' => ['sometimes', JsonApiRule::toOne()],
            'technician' => ['sometimes', 'nullable', JsonApiRule::toOne()],
        ];
    }
}
