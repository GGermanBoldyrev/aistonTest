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
            'description' => $this->resource->description,
            'user_id' => $this->resource->user_id,
            'isWarrantyCase' => $this->resource->is_warranty_case,

            'reactedAt' => $this->resource->reacted_at,
            'resolvedAt' => $this->resource->resolved_at,
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
            $this->relation('pharmacy'),
            $this->relation('priority'),
            $this->relation('category'),
            $this->relation('technician'),
            $this->relation('status'),
            $this->relation('attachments'),
        ];
    }
}
