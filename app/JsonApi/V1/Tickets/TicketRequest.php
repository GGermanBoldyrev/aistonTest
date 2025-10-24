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
        $uniqueNumber = Rule::unique('tickets', 'number');
        if ($this->model()) {
            $uniqueNumber->ignore($this->model()->getKey());
        }

        return [
            'number' => ['required', 'string', 'max:255', $uniqueNumber],
            'topic' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'isWarrantyCase' => ['sometimes', 'boolean'],

            'pharmacy' => ['required', JsonApiRule::toOne()],
            'priority' => ['required', JsonApiRule::toOne()],
            'category' => ['required', JsonApiRule::toOne()],
            'status' => ['sometimes', JsonApiRule::toOne()],
            'technician' => ['sometimes', 'nullable', JsonApiRule::toOne()],
        ];
    }
}
