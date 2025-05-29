<?php

use Examyou\RestAPI\Facades\ApiRoute;

// Admin Routes
ApiRoute::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    ApiRoute::get('all-langs', ['as' => 'api.extra.all-langs', 'uses' => 'AuthController@allEnabledLangs']);
    ApiRoute::get('lang-trans', ['as' => 'api.extra.lang-trans', 'uses' => 'AuthController@langTrans']);
    ApiRoute::post('change-theme-mode', ['as' => 'api.extra.change-theme-mode', 'uses' => 'AuthController@changeThemeMode']);
    ApiRoute::get('all-users', ['as' => 'api.extra.all-users', 'uses' => 'AuthController@allUsers']);

    // Check visibility of module according to subscription plan
    ApiRoute::post('check-subscription-module-visibility', ['as' => 'api.extra.check-subscription-module-visibility', 'uses' => 'AuthController@checkSubscriptionModuleVisibility']);
    ApiRoute::post('visible-subscription-modules', ['as' => 'api.extra.visible-subscription-modules', 'uses' => 'AuthController@visibleSubscriptionModules']);

    ApiRoute::group(['middleware' => ['api.auth.check']], function () {
        ApiRoute::post('dashboard', ['as' => 'api.extra.dashboard', 'uses' => 'AuthController@dashboard']);
        ApiRoute::post('upload-file', ['as' => 'api.extra.upload-file', 'uses' => 'AuthController@uploadFile']);
        ApiRoute::post('download-file', ['as' => 'api.extra.download-file', 'uses' => 'AuthController@downloadFile']);
        ApiRoute::post('delete-file', ['as' => 'api.extra.delete-file', 'uses' => 'AuthController@deleteFile']);
        ApiRoute::post('profile', ['as' => 'api.extra.profile', 'uses' => 'AuthController@profile']);
        ApiRoute::post('user', ['as' => 'api.extra.user', 'uses' => 'AuthController@user']);
        ApiRoute::get('timezones', ['as' => 'api.extra.user', 'uses' => 'AuthController@getAllTimezones']);
    });

    // Routes Accessable to thouse user who have permissions realted to route
    ApiRoute::group(['middleware' => ['api.permission.check', 'api.auth.check']], function () {
        $options = [
            'as' => 'api'
        ];

        // Imports
        ApiRoute::post('users/import', ['as' => 'api.users.import', 'uses' => 'UsersController@import']);

        ApiRoute::get('select-options/{group}', ['as' => 'api.select-options.group', 'uses' => 'SelectOptionController@getGroupOptions']);

        // Update Status
        ApiRoute::post('email-templates/update-status', ['as' => 'api.email-templates.update-status', 'uses' => 'EmailTemplateController@updateStatus']);
        ApiRoute::post('forms/update-status', ['as' => 'api.forms.update-status', 'uses' => 'FormController@updateStatus']);
        ApiRoute::post('form-field-names/update-status', ['as' => 'api.form-field-name.update-status', 'uses' => 'FormFieldNameController@updateStatus']);

        // Lead Follow Up
        ApiRoute::post('lead-follow-ups/take-action', ['as' => 'api.lead-follow-ups.take-action', 'uses' => 'LeadFollowUpController@takeFollowUpAction']);
        ApiRoute::post('lead-follow-ups/delete', ['as' => 'api.lead-follow-ups.delete', 'uses' => 'LeadFollowUpController@delete']);
        ApiRoute::resource('lead-follow-ups', 'LeadFollowUpController', ['as' => 'api', 'only' => ['index']]);

        // Salesman Booking
        ApiRoute::post('salesman-bookings', ['as' => 'api.salesman-bookings.delete', 'uses' => 'SalesmanBookingController@delete']);
        ApiRoute::resource('salesman-bookings', 'SalesmanBookingController', ['as' => 'api', 'only' => ['index']]);

        // All Forms
        ApiRoute::get('forms/all', ['as' => 'api.forms.all', 'uses' => 'FormController@allForms']);
        ApiRoute::resource('forms', 'FormController', $options);

        // Call Manager
        ApiRoute::resource('call-managers', 'CallManagerController', ['as' => 'api', 'only' => ['index']]);

        ApiRoute::post('leads/start-follow-up', ['as' => 'api.leads.start-follow-up', 'uses' => 'LeadController@startFollowUp']);

        ApiRoute::delete('deleteFile/{fileName}', ['as' => 'api.document.deleteFile', 'uses' => 'DocumentController@deleteFile']);
        ApiRoute::post('documents/generate-pdf', ['as' => 'api.documents.generate-pdf', 'uses' => 'DocumentController@generatePDF']);
        ApiRoute::post('documents/send-envelope', ['as' => 'api.documents.send-for-signature', 'uses' => 'DocumentController@sendEnvelope']);
        ApiRoute::get('documents/download-envelope/{id}/{status}/{certificate}', ['as' => 'api.documents.download-envelope', 'uses' => 'DocumentController@downloadEnvelope']);
        ApiRoute::post('documents/void-envelope', ['as' => 'api.documents.void-signature', 'uses' => 'DocumentController@voidEnvelope']);
        ApiRoute::post('documents/send-reminder', ['as' => 'api.documents.send-reminder', 'uses' => 'DocumentController@sendReminder']);
        ApiRoute::get('documents/fetch-envelopes/{individualXid}', ['as' => 'api.documents.', 'uses' => 'DocumentController@fetchEnvelopes']);
        ApiRoute::resource('documents', 'DocumentController', $options);

        ApiRoute::post('leads/send-email', ['as' => 'api.leads.send-email', 'uses' => 'LeadController@sendEmail']);
        ApiRoute::get('leads/campaign-stats', ['as' => 'api.leads.campaign-stats', 'uses' => 'LeadController@leadCampaignStats']);
        ApiRoute::get('leads/campaign-members', ['as' => 'api.leads.bookings.lead-campaign-members', 'uses' => 'LeadController@leadCampaignMembers']);
        ApiRoute::post('leads/create-booking', ['as' => 'api.leads.bookings.create', 'uses' => 'LeadController@createBooking']);
        ApiRoute::get('leads/create-call-log/{id}', ['as' => 'api.leads.create-call-log', 'uses' => 'LeadController@createLeadCallLog']);
        ApiRoute::post('leads/create-lead', ['as' => 'api.leads.create-lead', 'uses' => 'LeadController@createLead']);
        ApiRoute::resource('leads', 'LeadController', ['as' => 'api', 'only' => ['index', 'show']]);

        ApiRoute::get('campaigns/email-templates', ['as' => 'api.campaigns.email-templates', 'uses' => 'CampaignController@campaignEmailTemplates']);
        ApiRoute::get('campaigns/user-campaigns', ['as' => 'api.campaigns.user-campaigns', 'uses' => 'CampaignController@userCampaigns']);
        ApiRoute::post('campaigns/skip-delete-lead', ['as' => 'api.campaigns.skip-delete-lead', 'uses' => 'CampaignController@skipAndDeleteLead']);
        ApiRoute::post('campaigns/update-lead-timer', ['as' => 'api.campaigns.update-lead-timer', 'uses' => 'CampaignController@updateTimerLead']);
        ApiRoute::post('campaigns/update-actioned-lead', ['as' => 'api.campaigns.update-actioned-lead', 'uses' => 'CampaignController@updateActionedLead']);
        ApiRoute::post('campaigns/update-actioned-sale', ['as' => 'api.campaigns.update-actioned-sale', 'uses' => 'CampaignController@updateActionedSale']);
        ApiRoute::post('campaigns/take-lead-action', ['as' => 'api.campaigns.take-lead-action', 'uses' => 'CampaignController@takeLeadAction']);
        ApiRoute::post('campaigns/take-action', ['as' => 'api.campaigns.take-action', 'uses' => 'CampaignController@takeAction']);
        ApiRoute::post('campaigns/stop', ['as' => 'api.campaigns.stop', 'uses' => 'CampaignController@stopCampaign']);
        ApiRoute::resource('campaigns', 'CampaignController', $options);

        ApiRoute::get('email-templates/all', ['as' => 'api.email-templates.all', 'uses' => 'EmailTemplateController@allEmailTemplates']);
        ApiRoute::resource('email-templates', 'EmailTemplateController', $options);

        ApiRoute::get('form-field-names/all', ['as' => 'api.form-field-names.all', 'uses' => 'FormFieldNameController@allFormFieldNames']);
        ApiRoute::resource('form-field-names', 'FormFieldNameController', $options);

        // Create Menu Update
        ApiRoute::post('companies/update-create-menu', ['as' => 'api.companies.update-create-menu', 'uses' => 'CompanyController@updateCreateMenu']);

        ApiRoute::resource('salesmans', 'SalesmanController', $options);
        ApiRoute::resource('expense-categories', 'ExpenseCategoryController', $options);
        ApiRoute::resource('expenses', 'ExpenseController', $options);
        ApiRoute::resource('products', 'ProductController', $options);
        ApiRoute::resource('users', 'UsersController', $options);
        ApiRoute::resource('companies', 'CompanyController', ['as' => 'api', 'only' => ['update']]);
        ApiRoute::resource('permissions', 'PermissionController', ['as' => 'api', 'only' => ['index']]);
        ApiRoute::resource('roles', 'RolesController', $options);

        // New Routes
        ApiRoute::resource('lead-statuses', 'LeadStatusController', $options);
        ApiRoute::resource('sale-statuses', 'SaleStatusController', $options);

        ApiRoute::resource('individual-logs', 'IndividualLogController', $options);

        // Sales
        ApiRoute::post('sales/transfer', ['as' => 'api.sales.transfer', 'uses' => 'SaleController@transferSales']);
        ApiRoute::post('sales/create-sale', ['as' => 'api.sales.create-sale', 'uses' => 'SaleController@createSale']);
        ApiRoute::get('sales/export', ['as' => 'api.sales.export', 'uses' => 'SaleController@export']);
        ApiRoute::resource('sales', 'SaleController', ['as' => 'api', 'only' => ['index', 'show', 'destroy']]);

        ApiRoute::post('addresses/save', ['as' => 'api.address.saveAddressData', 'uses' => 'AddressController@addEdit']);
        ApiRoute::resource('individuals', 'IndividualController', ['as' => 'api', 'only' => ['index', 'show']]);

        ApiRoute::resource('notes', 'NoteController', $options);

        ApiRoute::get('states/all', ['as' => 'api.states.all', 'uses' => 'StateController@allOptions']);

        ApiRoute::resource('activity-logs', 'ActivityLogController', $options);
    });
});
