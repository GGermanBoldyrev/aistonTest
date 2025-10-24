<?php

namespace App\JsonApi\V1\Technicians;

use App\Models\Technician;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Resources\JsonApiResource;

/**
 * @property Technician $resource
 */
class TechnicianResource extends JsonApiResource
{

    /**
     * Get the resource's attributes.
     *
     * @param Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        return [
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param Request|null $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        return [
            'tickets' => $this->relation('tickets'),
        ];
    }

}
