<?php

namespace App\Classes;

use Spatie\Permission\Models\Permission;

class PermsSeed
{
    public static $mainPermissionsArray = [
        'users_view',
        'users_create',
        'users_edit',
        'users_delete',
        'salesmans_view',
        'salesmans_create',
        'salesmans_edit',
        'salesmans_delete',
        'campaigns_view',
        'campaigns_view_all',
        'view_completed_campaigns',
        'campaigns_create',
        'campaigns_edit',
        'campaigns_delete',
        'leads_view_all',
        'leads_create',
        'leads_delete',
        'email_templates_view',
        'email_templates_view_all',
        'email_templates_create',
        'email_templates_edit',
        'email_templates_delete',
        'forms_view',
        'forms_view_all',
        'forms_create',
        'forms_edit',
        'forms_delete',
        'form_field_names_view',
        'form_field_names_create',
        'form_field_names_edit',
        'form_field_names_delete',
         'expense_categories_view',
        'expense_categories_create',
        'expense_categories_edit',
        'expense_categories_delete',
        'expenses_view',
        'expenses_create',
        'expenses_edit',
        'expenses_delete',

        // Product
        'products_view',
        'products_create',
        'products_edit',
        'products_delete',
        'currencies_view',
        'currencies_create',
        'currencies_edit',
        'currencies_delete',
        'roles_view',
        'roles_create',
        'roles_edit',
        'roles_delete',
        'companies_edit',
        'translations_view',
        'translations_create',
        'translations_edit',
        'translations_delete',
        'storage_edit',
        'email_edit',
    ];

    public static function seedPermissions()
    {
        $permissions = self::$mainPermissionsArray;;

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }
    }

    public static function seedMainPermissions()
    {
        // Main Module
        self::seedPermissions();
    }
}