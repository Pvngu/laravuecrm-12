<?php

namespace App\Listeners;

use App\Models\Sale;
use App\Classes\Common;
use App\Events\LeadStatusChanged;

class CreateSaleOnLeadStatusChanged
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeadStatusChanged $event): void
    {
        $individualId = $event->lead->individual_id;
        $existingSale = Sale::where('individual_id', $individualId)->first();
        $loggedUser = user();

        if($event->lead->lead_status == 1 && !$existingSale) {
            Sale::create([
                'individual_id' => $individualId,
                'assigned_to' => $loggedUser->id,
                'created_by' => $loggedUser->id,
            ]);
            
            Common::storeIndividualLog($individualId, 'updated_co');
        } else if($event->lead->lead_status == 2 && $existingSale) {
            $existingSale->update([
                'sale_status_id' => 4,
            ]);
        }
    }
}
