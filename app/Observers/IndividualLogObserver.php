<?php

namespace App\Observers;

use App\Models\IndividualLog;
use App\Classes\Common;

class IndividualLogObserver
{
    public function creating(IndividualLog $IndividualLog)
    {
        $user = user();

        $IndividualLog->created_by_id = $user->id;
    }

    public function updating(IndividualLog $IndividualLog)
    {
        $user = user();

        $IndividualLog->updated_by_id = $user->id;
    }

    public function saving(IndividualLog $IndividualLog)
    {
        $company = company();

        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if ($company && !$company->is_global) {
            $IndividualLog->company_id = company()->id;
        }
    }

    public function deleting(IndividualLog $IndividualLog)
    {
        Common::storeIndividualLog($IndividualLog->individual_id, 'deleted_notes', $IndividualLog->notes);
    }
}
