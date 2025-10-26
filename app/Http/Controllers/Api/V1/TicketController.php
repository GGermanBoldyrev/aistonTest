<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Tickets\CreateTicketWithAttachmentsAction;
use App\Http\Controllers\Controller;
use App\JsonApi\V1\Tickets\TicketRequest;
use App\JsonApi\V1\Tickets\TicketSchema;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class TicketController extends Controller
{

    use Actions\FetchMany;
    use Actions\FetchOne;
    //use Actions\Store;
    use Actions\Update;
    use Actions\Destroy;
    use Actions\FetchRelated;
    use Actions\FetchRelationship;
    use Actions\UpdateRelationship;
    use Actions\AttachRelationship;
    use Actions\DetachRelationship;

    public function store(
        TicketSchema $schema,
        TicketRequest $request,
        CreateTicketWithAttachmentsAction $action
    ): DataResponse {
        $validated = $request->validated();

        $attachmentUuids = (array) data_get($request->input('data'), 'attributes.ticketAttachments', []);

        $ticket = DB::transaction(function () use ($schema, $validated, $action, $attachmentUuids) {
            $ticket = $schema->repository()->create()->store($validated);

            $action->attach($ticket, $attachmentUuids);

            return $ticket->fresh(['attachments']);
        });

        return DataResponse::make($ticket)->didCreate();
    }
}
