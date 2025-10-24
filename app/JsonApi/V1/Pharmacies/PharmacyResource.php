<?php

namespace App\JsonApi\V1\Pharmacies;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Resources\JsonApiResource;

/**
 * @property Pharmacy $resource
 */
class PharmacyResource extends JsonApiResource
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
            'code' => $this->resource->code,
            'address' => $this->resource->address,
            'city' => $this->resource->city,
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
