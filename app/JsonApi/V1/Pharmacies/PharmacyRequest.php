<?php

namespace App\JsonApi\V1\Pharmacies;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class PharmacyRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        $uniqueCode = Rule::unique('pharmacies', 'code')
            ->ignore($this->model()?->id);

        if ($this->isCreating()) {
            return [
                'code' => ['required', 'string', 'max:255', $uniqueCode],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:255'],
            ];
        }

        return [
            'code' => ['sometimes', 'required', 'string', 'max:255', $uniqueCode],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
