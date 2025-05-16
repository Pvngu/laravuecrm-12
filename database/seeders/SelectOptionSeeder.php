<?php

namespace Database\Seeders;

use App\Models\SelectOption;
use Illuminate\Database\Seeder;

class SelectOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $selectOptionData = [
            [
                'group' => 'enrollment_plan',
                'key' => 'ach',
                'value' => 'enrollment.ach',
            ],
            [
                'group' => 'enrollment_plan',
                'key' => 'credit_card',
                'value' => 'common.credit_card',
            ],
            [
                'group' => 'payment',
                'key' => 'paid',
                'value' => 'common.paid',
            ],
            [
                'group' => 'payment',
                'key' => 'pending',
                'value' => 'common.pending',
            ],
            [
                'group' => 'payment',
                'key' => 'canceled',
                'value' => 'common.canceled',
            ],
            [
                'group' => 'payment',
                'key' => 'rejected',
                'value' => 'common.rejected',
            ],
            [
                'group' => 'language',
                'key' => 'English',
                'value' => 'common.english',
            ],
            [
                'group' => 'language',
                'key' => 'Spanish',
                'value' => 'common.spanish',
            ],
            [
                'group' => 'bank_account',
                'key' => 'checking',
                'value' => 'bank.checking',
            ],
            [
                'group' => 'bank_account',
                'key' => 'savings',
                'value' => 'bank.savings',
            ],
            [
                'group' => 'bank_account',
                'key' => 'money_market',
                'value' => 'bank.money_market',
            ],
            [
                'group' => 'bank_account',
                'key' => 'certificate_of_deposit',
                'value' => 'bank.certificate_of_deposit',
            ],
            [
                'group' => 'credit_card',
                'key' => 'visa',
                'value' => 'Visa',
            ],
            [
                'group' => 'credit_card',
                'key' => 'mastercard',
                'value' => 'Mastercard',
            ],
            [
                'group' => 'credit_card',
                'key' => 'american_express',
                'value' => 'American Express',
            ],
            [
                'group' => 'credit_card',
                'key' => 'discover',
                'value' => 'Discover',
            ],
            [
                'group' => 'debt',
                'key' => 'applicant',
                'value' => 'lead.applicant',
            ],
            [
                'group' => 'debt',
                'key' => 'co_applicant',
                'value' => 'lead.co_applicant',
            ],
            [
                'group' => 'debt',
                'key' => 'joint',
                'value' => 'lead.joint',
            ]
        ];
        

        SelectOption::insert($selectOptionData);
    
    }
}
