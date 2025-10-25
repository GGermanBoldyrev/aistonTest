<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateTicketWithAttachmentsAction
{
    public function attach($ticket, array $uuids): void
    {
        foreach ($uuids as $uuid) {
            $tempFile = collect(Storage::disk('local')->files('tmp'))
                ->first(fn($f) => Str::startsWith(basename($f), $uuid));

            if (!$tempFile) continue;

            $name = basename($tempFile);

            $permanent = "tickets/{$ticket->id}/{$name}";

            Storage::disk('public')->put($permanent, Storage::disk('local')->get($tempFile));
            Storage::disk('local')->delete($tempFile);

            $ticket->attachments()->create([
                'disk' => 'public',
                'path' => $permanent,
                'original_name' => $name,
                'mime' => Storage::disk('public')->mimeType($permanent),
                'size' => Storage::disk('public')->size($permanent),
            ]);
        }
    }
}
