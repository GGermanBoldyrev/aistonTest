<?php

namespace App\JsonApi\V1\Technicians;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class TechnicianRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        $uniqueEmail = Rule::unique('technicians', 'email')->ignore($this->model()?->id);

        if ($this->isCreating()) {
            return [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', $uniqueEmail],
            ];
        }

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', $uniqueEmail],
        ];
    }

}
