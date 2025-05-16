<?php

namespace App\Http\Controllers\Api;

use App\Models\Debt;
use App\Models\Note;
use App\Classes\Common;
use App\Models\Individual;
use Examyou\RestAPI\ApiResponse;
use App\Http\Controllers\ApiBaseController;

class NoteController extends ApiBaseController
{
    protected $model = Note::class;

    public function modifyIndex($query) {
        $request = request();
        $user = user();

        $query = $query->join('individuals', 'individuals.id', '=', 'notes.noteable_id')
            ->join('campaigns', 'campaigns.id', 'individuals.campaign_id');

        if($request->has('individual_id') && $request->individual_id != "") {
            $individualId = $this->getIdFromHash($request->individual_id);

            $query = $query->where('noteable_type', Individual::class)
                ->where('noteable_id', $individualId);
        }
        else if($request->has('debt_id') && $request->debt_id != "") {
            $debtId = $this->getIdFromHash($request->debt_id);

            $query = $query->where('noteable_type', Debt::class)
                ->where('noteable_id', $debtId);
        }

        if($request->has('only_alerts') && $request->only_alerts == "true") {
            $query = $query->whereNotNull('alert_type');
        }

        // Extra Filters
        if ($user->ability('admin', 'view_completed_campaigns')) {
            if ($request->has('campaign_status') && $request->campaign_status == "completed") {
                $query = $query->where('campaigns.status', '=', 'completed');
            } else {
                $query = $query->where('campaigns.status', '!=', 'completed');
            }
        } else {
            $query = $query->where('campaigns.status', '!=', 'completed');
        }

        if($request->has('type') && $request->type = "individual") {
            $query = $query->where('noteable_type', Individual::class);
        }

        if ($request->has('campaign_id') && $request->campaign_id != "") {
            $campaignId = $this->getIdFromHash($request->campaign_id);

            $query = $query->where('individuals.campaign_id', $campaignId);
        }

        // Dates Filters
        if ($request->has('dates') && $request->dates != "") {
            $dates = explode(',', $request->dates);
            $startDate = $dates[0];
            $endDate = $dates[1];

            $query = $query->whereRaw('notes.created_at >= ?', [$startDate])
                ->whereRaw('notes.created_at <= ?', [$endDate]);
        }

        return $query;
    }

    public function storing($note) {
        $loggedUser = user();
        $request = request();

        if($request->has('individual_id') && $request->individual_id != "") {
            $individualId = $this->getIdFromHash($request->individual_id);

            $note->noteable_type = Individual::class;
            $note->noteable_id = $individualId;
        } 
        else if($request->has('debt_id') && $request->debt_id != "") {
            $debtId = $this->getIdFromHash($request->debt_id);

            $note->noteable_type = Debt::class;
            $note->noteable_id = $debtId;
        }

        $note->created_by_id = $loggedUser->id;
        return $note;
    }

    public function updating($note) {
        $loggedUser = user();
        $request = request();

        if($request->has('alert_type') && $request->alert_type != "" && strlen($request->content) > 80) {
            $note->content = substr($request->content, 0, 80);
        }
        
        $note->updated_by_id = $loggedUser->id;

        return $note;
    }
}
