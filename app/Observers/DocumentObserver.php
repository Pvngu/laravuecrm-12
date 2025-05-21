<?php

namespace App\Observers;

use App\Models\Document;
use App\Classes\Common;

class DocumentObserver
{
    /**
     * Handle the Document "deleting" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function deleting(Document $document)
    {
        if ($document->file) {
            // Assuming 'documents' is the folder string used for document files
            (new Common())->deleteFile($document->file, 'documents');
        }
    }
}
