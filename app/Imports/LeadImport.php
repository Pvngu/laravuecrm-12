<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Lead;
use App\Classes\Common;
use App\Models\Campaign;
use App\Models\Individual;
use Illuminate\Support\Str;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadImport implements ToArray, WithHeadingRow
{
    public $importLeadFields = "";
    public $allFormFields = "";
    public $campaignId = "";

    public function __construct($importLeadFields, $allFormFields, $campaignId)
    {
        $this->importLeadFields = $importLeadFields;
        $this->allFormFields = $allFormFields;
        $this->campaignId = $campaignId;
    }

    public function array(array $leads)
    {
        // Adding defined fields so it can be saved in lead data
        $fields = [
            'Reference Number' => 'text',
            'First Name' => 'text',
            'Last Name' => 'email',
            'SSN' => 'text',
            'Date of Birth' => 'text',
            'Home Phone' => 'phone',
            'Language' => 'text',
            'Phone Number' => 'phone',
            'Email' => 'email',
            'Original Profile Id' => 'text',
        ];

        foreach($fields as $name => $type) {
            $this->allFormFields[$name] = [
                'name' => $name,
                'type' => $type,
                'required' => 0
            ];
        }

        $campaign = Campaign::withoutGlobalScope(CompanyScope::class)->find($this->campaignId);

        DB::transaction(function () use ($leads, $campaign) {

            $counter = 1;
            foreach ($leads as $lead) {

                // Calculating Lead Data Hash
                $leadHashString = "";
                $newLeadData = [];
                foreach ($this->allFormFields as $allFormField) {
                    $fieldName = $allFormField['name'];
                    $headerColumnName = array_search($fieldName, $this->importLeadFields);

                    if ($headerColumnName === false) {
                        $fieldValue = "";
                    } else {
                        $headerColumnSlug = Str::slug($headerColumnName, '_');
                        $fieldValue = $lead[$headerColumnSlug] ? $lead[$headerColumnSlug] : '';
                    }

                    if(!in_array($fieldName, ['Reference Number' ,'First Name', 'Last Name', 'SSN', 'Date of Birth', 'Home Phone', 'Language', 'Phone Number', 'Email', 'Original Profile Id'])) {
                        $newLeadData[] = [
                            'id' => strtolower(Str::random(12)),
                            'field_name' => $fieldName,
                            'field_value' => $fieldValue,
                        ];
                    }

                    $leadHashString .= strtolower($fieldValue);

                    switch ($fieldName) {
                        case 'Language':
                            $language = $fieldValue;
                            break;
                        case 'First Name':
                            $firstName = $fieldValue;
                            break;
                        case 'Last Name':
                            $lastName = $fieldValue;
                            break;
                        case 'SSN':
                            $ssn = $fieldValue;
                            break;
                        case 'Date of Birth':
                            $dateOfBirth = $fieldValue;
                            break;
                        case 'Home Phone':
                            $homePhone = $fieldValue;
                            break;
                        case 'Phone Number':
                            $phoneNumber = $fieldValue;
                            break;
                        case 'Email':
                            $email = $fieldValue;
                            break;
                        case 'Original Profile Id':
                            $originalProfileId = $fieldValue;
                            break;
                    }
                }

                $leadHash = md5($leadHashString . $campaign->id);

                // Check if lead hash exists or not
                $leadHashCount = Lead::withoutGlobalScope(CompanyScope::class)
                    ->join('individuals', 'individuals.id', '=', 'leads.individual_id')
                    ->where('individuals.campaign_id', $campaign->id)
                    ->where('lead_hash', $leadHash)
                    ->count();

                if ($leadHashCount == 0) {

                    // Saving Lead
                    $lead = new Lead();
                    $individual = new Individual();
                    $individual->campaign_id = $campaign->id;

                    // Reference Prefix
                    if ($campaign->allow_reference_prefix) {
                        $timerCounter = Carbon::now()->timestamp + $counter;
                        $individual->reference_number = $campaign->reference_prefix . $timerCounter;
                    }

                    if(isset($language)) {
                        $individual->language = $language;
                    }

                    if(isset($firstName)) {
                        $individual->first_name = $firstName;
                    }

                    if(isset($lastName)) {
                        $individual->last_name = $lastName;
                    }

                    if(isset($ssn)) {
                        $individual->ssn = $ssn;
                    }

                    if(isset($dateOfBirth)) {
                        $individual->date_of_birth = $dateOfBirth;
                    }

                    if(isset($homePhone)) {
                        $individual->home_phone = $homePhone;
                    }

                    if(isset($phoneNumber)) {
                        $individual->phone_number = $phoneNumber;
                    }

                    if(isset($email)) {
                        $individual->email = $email;
                    }

                    $individual->lead_data = $newLeadData;
                    $individual->created_by = user() ?  user()->id : null;
                    $lead->lead_hash = $leadHash;
                    
                    $individual->save();
                    $lead->individual_id = $individual->id;
                    $lead->save();

                    $counter = $counter + 1;
                }
            }

            // Calculating Lead Counts
            Common::recalculateCampaignLeads($campaign->id);
        });
    }
}
