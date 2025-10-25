<?php

namespace App\JsonApi\V1\Categories;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class CategoryRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        $uniqueName = Rule::unique('categories', 'name')->ignore($this->model()?->id);

        if ($this->isCreating()) {
            return [
                'name' => ['required', 'string', 'max:255', $uniqueName],
            ];
        }

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', $uniqueName],
        ];
    }

}
