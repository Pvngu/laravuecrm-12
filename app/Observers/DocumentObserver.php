<?php

namespace App\Observers;

use App\Models\Document;
use App\Classes\Common;

class DocumentObserver
{
    public function deleting(Document $Document)
    {
        $notes = json_encode([
            'title' => $Document->title,
            'type' => $Document->type
        ]);

        Common::storeIndividualLog($Document->individual_id, 'deleted_doc', $notes);
    }
}
