<?php

namespace Database\Seeders;

use App\Models\SaleStatus;
use Illuminate\Database\Seeder;

class SaleStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    $saleStatusesData = [
        ['name' => 'New Lead'],
        ['name' => 'Follow Up'],
        ['name' => 'Introductory Call'],
        ['name' => 'Not Interested'],
    ];

    SaleStatus::insert($saleStatusesData);
    
    }
}
