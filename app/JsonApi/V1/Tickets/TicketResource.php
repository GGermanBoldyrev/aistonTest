<?php

namespace App\JsonApi\V1\Tickets;

use App\Models\Ticket;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Resources\JsonApiResource;

/**
 * @property Ticket $resource
 */
class TicketResource extends JsonApiResource
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
            'number' => $this->resource->number,
            'topic' => $this->resource->topic,
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
            'pharmacy' => $this->relation('pharmacy'),
            'priority' => $this->relation('priority'),
            'category' => $this->relation('category'),
            'technician' => $this->relation('technician'),
            'status' => $this->relation('status'),
        ];
    }

}
