<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    $leadStatusesData = [
        ['name' => 'Interested'],
        ['name' => 'Not Interested'],
        ['name' => 'Unreachable'],
    ];

    LeadStatus::insert($leadStatusesData);
    
    }
}
