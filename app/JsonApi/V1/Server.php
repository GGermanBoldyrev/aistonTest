<?php

namespace App\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{

    /**
     * The base URI namespace for this server.
     *
     * @var string
     */
    protected string $baseUri = '/api/v1';

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            \App\JsonApi\V1\Priorities\PrioritySchema::class,
            \App\JsonApi\V1\Categories\CategorySchema::class,
            \App\JsonApi\V1\Statuses\StatusSchema::class,
            \App\JsonApi\V1\Technicians\TechnicianSchema::class,
            \App\JsonApi\V1\Pharmacies\PharmacySchema::class,
            \App\JsonApi\V1\Tickets\TicketSchema::class,
            \App\JsonApi\V1\CategoryHints\CategoryHintSchema::class,
        ];
    }
}
