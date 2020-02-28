<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);


Route::get('/test', function(){
    return Route::currentRouteName();
})->name('test.route');

Route::get('/parse', "ParseLogController@parse");
// Route::get('/invoice/test', "InvoiceController@show");
Route::get('/testreport', "ParseLogController@testReport");



Route::group(['middleware' => ['watch_dog']], function () {

    // Admin Routes
    Route::group(['middleware' => ['auth']], function () {

        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/profile', 'ProfileController@profile')->name('user.profile');
        Route::get('/password/change', 'UserController@showPasswordForm')->name('password.change.request')->middleware('password.confirm');
        Route::post('/password/change', 'UserController@changePassword')->name('password.change');

        // ACL Routes
        Route::group(['middleware' => ['role:Super Admin']], function () {

            // Users Routes
            Route::resource('users', "UserController");
            Route::get('/users/delete/{user}', "UserController@destroy")->middleware('permission:delete users');
            Route::get('/user/all', 'UserController@users')->name('users')->middleware('permission:read users');
            Route::get('/user/datatable', "UserController@dataTable")->name('users.datatable')->middleware('permission:read users');


            // Roles Routes
            Route::resource('roles', "RoleController");
            Route::get('/role/all', 'RoleController@roles')->name('roles')->middleware('permission:read roles');
            Route::post('assignRole', "RoleController@assignRole")->name('assignRole')->middleware('permission:assign roles');
            Route::get('/role/datatable', "RoleController@dataTable")->name('roles.datatable')->middleware('permission:read roles');

            // Permissions Routes
            Route::resource('permissions', "PermissionController");
            Route::get('/permission/all', "PermissionController@permissions")->name('permissions')->middleware('permission:read permissions');
            Route::post('/assignPermissions', "PermissionController@assignPermissions")->name('assignPermissions')->middleware('permission:assign permissions');
            Route::post('/getUserPermissions', "PermissionController@getUserPermissions")->name('getUserPermissions')->middleware('permission:read user-permissions');
            Route::post('/getRolePermissions', "PermissionController@getRolePermissions")->name('getRolePermissions')->middleware('permission:read role-permissions');

        });

        // Currency Routes
        Route::post('/getCurrency', 'CurrencyController@show')->name('getCurrency')->middleware('permission:read currencies');
        Route::get('/currencies', 'CurrencyController@index')->name('currencies.index')->middleware('permission:read currencies');
        Route::post('/currencies', 'CurrencyController@store')->name('currencies.store')->middleware('permission:create currencies');
        Route::get('/currencies/all', 'CurrencyController@currencies')->name('currencies')->middleware('permission:read currencies');
        Route::put('/currencies/{currency}', 'CurrencyController@update')->name('currencies.update')->middleware('permission:update currencies');
        Route::delete('/currencies/{currency}', 'CurrencyController@destroy')->name('currencies.delete')->middleware('permission:delete currencies');
        Route::get('/currenceies/datatable', "CurrencyController@dataTable")->name('currencies.datatable')->middleware('permission:read currencies');

        // Tariff Routes
        Route::get('/tariffnames', 'TariffNameController@index')->name('tariffname.index')->middleware('permission:read tariff');
        Route::post('/tariffnames', 'TariffNameController@store')->name('tariffname.store')->middleware('permission:create tariff');
        Route::post('/tariffname/show', 'TariffNameController@show')->name('tariffname.show')->middleware('permission:read tariff');
        Route::get('/tariffnames/all', 'TariffNameController@tariffNames')->name('tariffnames')->middleware('permission:read tariff');
        Route::put('/tariffnames/{tariffName}', 'TariffNameController@update')->name('tariffname.update')->middleware('permission:update tariff');
        Route::get('/tariffnames/datatable', 'TariffNameController@dataTable')->name('tariffnames.datatable')->middleware('permission:read tariff');
        Route::delete('/tariffnames/{tariffName}', 'TariffNameController@destroy')->name('tariffnames.delete')->middleware('permission:delete tariff');

        // Tariff Rates Routes
        Route::post('tariffnames/rates', "RateController@store")->name('rate.store')->middleware('permission:create rates');
        Route::post('tariffnames/rate/import/', 'RateController@import')->name('rate.import')->middleware('permission:import rates');
        Route::get('/tariffnames/rate/show/{rate}', "RateController@show")->name('rate.show')->middleware('permission:read rates');
        Route::delete('tariffnames/rates/{rate}', 'RateController@destroy')->name('rate.delete')->middleware('permission:delete rates');
        Route::get('/tariffnames/{tariffname}/rates', 'RateController@index')->name('rate.index')->middleware('permission:read rates');
        Route::get('tariffnames/rate/download/', 'RateController@download')->name('rate.download')->middleware('permission:export rates');
        Route::put('tariffnames/{tariffname}/rate/{id}', 'RateController@update')->name('rate.update')->middleware('permission:update rates');
        Route::get('getTariffRates/{tariffname}', 'RateController@getRatesTable')->name('getTariffRates')->middleware('permission:read rates');
        Route::get('tariffnames/{tariffname}/rates/export', 'RateController@export')->name('rate.export')->middleware('permission:export rates');

        // Gateway Routes
        Route::get('/gateways', "GatewayController@index")->name('gateway.index')->middleware('permission:read gateways');
        Route::get('/gateways/all', 'GatewayController@gateways')->name('gateways')->middleware('permission:read gateways');
        Route::post('/gateways/show', 'GatewayController@show')->name('gateway.show')->middleware('permission:read gateways');
        Route::post('/gateways/store', "GatewayController@store")->name('gateway.store')->middleware('permission:create gateways');
        Route::put('/gateways/{gateway}', 'GatewayController@update')->name('gateway.update')->middleware('permission:update gateways');
        Route::post('/gateways/payments', 'GatewayController@payments')->name('gateway.payments')->middleware('permission:read gateway-payments');
        Route::delete('/gateways/{gateway}', 'GatewayController@destroy')->name('gateway.delete')->middleware('permission:delete gateways');
        Route::get('/gateways/datatable', 'GatewayController@dataTable')->name('gateways.datatable')->middleware('permission:read gateways');
        Route::post('/gateways/payment/store', 'GatewayController@paymentStore')->name('gateway.payment.store')->middleware('permission:create gateway-payments');

        // General Queries
        Route::get('/getCountries', "Homecontroller@getCountries")->name('countries')->middleware('permission:read countries');
        Route::get('/payment-types', 'Homecontroller@getPaymentTypes')->name('payment-types')->middleware('permission:read payment-types');

        // Clients Routes
        Route::get('/clients', "ClientController@index")->name('client.index')->middleware('permission:read clients');
        Route::get('/clients/all', "ClientController@clients")->name('clients')->middleware('permission:read clients');
        Route::post('/clients/show', "ClientController@show")->name('client.show')->middleware('permission:read clients');
        Route::post('/clients/store', "ClientController@store")->name('client.store')->middleware('permission:create clients');
        Route::put('/clients/{client}', "ClientController@update")->name('client.update')->middleware('permission:update clients');
        Route::post('/clients/payments', 'ClientController@payments')->name('client.payments')->middleware('permission:read client-payments');
        Route::delete('/clients/{client}', 'ClientController@destroy')->name('client.delete')->middleware('permission:delete clients');
        Route::get('/clients/datatable', "ClientController@dataTable")->name('clients.datatable')->middleware('permission:read clients');
        Route::post('/clients/payment/store', 'ClientController@paymentStore')->name('client.payment.store')->middleware('permission:create client-payments');

        // IP Routes
        Route::post('/clients/ips', "IpController@store")->name('ip.store')->middleware('permission:create ips');
        Route::post('/clients/ips/show', "IpController@show")->name('ip.show')->middleware('permission:read ips');
        Route::post('/clients/ips/get', "IpController@clientIps")->name('client.ips')->middleware('permission:read ips');
        Route::put('/clients/ips/update', "IpController@update")->name('ip.update')->middleware('permission:update ips');
        Route::delete('/clients/ips/{ip}', "IpController@destroy")->name('ip.delete')->middleware('permission:delete ips');
        Route::get('/clients/ips/all', "IpController@index")->name('ips.index')->middleware('permission:read ips');

        // Simulation Routes
        Route::post('/bill/simulate', "ParseLogController@simulate")->name('bill.simulate')->middleware('permission:simulate bill');
        Route::get('/bill/simulate', "ParseLogController@getSimulate")->name('bill.simulate.panel')->middleware('permission:simulate bill');

        // CDR Log Routes
        Route::get('/cdrlogs', "CdrLogController@index")->name('cdr.logs')->middleware('permission:read cdr-logs');
        Route::get('/cdrlogs/get', "CdrLogController@getCdrLogs")->name('get.cdr.logs')->middleware('permission:read cdr-logs');
        Route::get('/cdrlogs/show/{file_name}', "CdrLogController@show")->name('cdr.log.show')->middleware('permission:read cdr-logs');
        Route::get('/cdrlogs/reparse/{file_name}', "CdrLogController@reparse")->name('cdr.log.reparse')->middleware('permission:parse cdr-logs');

        // Reporting Routes
        Route::get('/summary/orig-term-calls', "ReportController@origTermCallsSearchPanel")->name('orig-term-calls.summary.panel')->middleware('permission:read calls-summary');
        Route::get('/summary/orig-term-calls/fetch', "ReportController@origTermSummary")->name('orig-term-calls.summary.fetch')->middleware('permission:read calls-summary');
        Route::get('/summary/orig-term-calls/export', "ReportController@exportOrigTermSummary")->name('orig-term-calls.summary.export')->middleware('permission:export calls-summary');
        Route::get('/summary/success-calls', "ReportController@successCallsSearchPanel")->name('success-calls.summary.panel')->middleware('permission:read calls-summary');
        Route::get('/summary/success-calls/fetch', "ReportController@successCallsSummary")->name('success-calls.summary.fetch')->middleware('permission:read calls-summary');
        Route::get('/summary/success-calls/export', "ReportController@exportSuccessSummary")->name('success-calls.summary.export')->middleware('permission:export calls-summary');
        Route::get('/summary/failed-calls', "ReportController@failedCallsSearchPanel")->name('failed-calls.summary.panel')->middleware('permission:read calls-summary');
        Route::get('/summary/failed-calls/fetch', "ReportController@failedCallsSummary")->name('failed-calls.summary.fetch')->middleware('permission:read calls-summary');
        Route::get('/summary/failed-calls/export', "ReportController@exportFailedSummary")->name('failed-calls.summary.export')->middleware('permission:export calls-summary');
        Route::get('/summary/loss-profit', "ReportController@lossProfitSearchPanel")->name('loss-profit.summary.panel')->middleware('permission:read calls-summary');
        Route::get('/summary/loss-profit/fetch', "ReportController@lossProfitSummary")->name('loss-profit.summary.fetch')->middleware('permission:read calls-summary');
        Route::get('/summary/loss-profit/export', "ReportController@exportlossProfitSummary")->name('loss-profit.summary.export')->middleware('permission:export calls-summary');

        // System Routes
        Route::prefix('/system')->name('system.')->namespace('System')->group(function () {
            // Activity log routes
            Route::get('/access-log/panel', "ActivityLogController@panel")->name('access-log.panel')->middleware('permission:read access-logs');
            Route::get('/access-log', "ActivityLogController@accessLogs")->name('access-log.fetch')->middleware('permission:read access-logs');
            Route::get('/access-log/{id}', "ActivityLogController@show")->name('access-log.show')->middleware('permission:read access-logs');
            Route::get('/access-logs/datatable', "ActivityLogController@dataTable")->name('access-log.datatable')->middleware('permission:read access-logs');
        });

        Route::prefix('/company')->name('company.')->namespace('System')->group(function () {
            // Company Routes
            Route::get('/', "CompanyController@index")->name('settings')->middleware('permission:read company-settings');
            Route::get('/datatable', "CompanyController@datatable")->name('settings.datatable')->middleware('permission:read company-settings');
            Route::get('/create', "CompanyController@create")->name('settings.create')
            // ->middleware('permission:create company-settings')
            ;
            Route::get('/all', "CompanyController@companies")->name('all')->middleware('permission:read company-settings');
            Route::get('/{company}/show', "CompanyController@view")->name('settings.view')->middleware('permission:read company-settings');
            Route::get('/{company}/edit', "CompanyController@edit")->name('settings.edit')->middleware('permission:update company-settings');
            Route::get('/{company}/delete', "CompanyController@delete")->name('settings.delete')
            // ->middleware('permission:delete company-settings')
            ;
            Route::post('/', "CompanyController@update")->name('settings.update')->middleware('permission:update company-settings');
            Route::get('/parivacy-policy', "CompanyController@test")->name('privacy-policy')->middleware('permission:read privacy-policy');
            Route::post('/parivacy-policy', "CompanyController@test")->name('privacy-policy')->middleware('permission:update privacy-policy');
        });

        Route::prefix('/invoice')->name('invoice.')->group(function () {
            // Invoice Routes
            Route::get('/generate', 'InvoiceController@draftFormShow')->name('generate.panel')
            // ->middleware('permission:create invoice')
            ;
            Route::post('/generate', 'InvoiceController@draft')->name('generate')
            // ->middleware('permission:create invoice')
            ;
            Route::get('/{invoice}/show', "InvoiceController@show")->name('show');
            // ->middleware('permission:read invoice')
            ;
            Route::get('/{invoice}/download', "InvoiceController@downloadPDF")->name('download');
            // ->middleware('permission:read invoice')
            ;
            Route::get('/{invoice}/pdf', "InvoiceController@viewPDF")->name('view-pdf');
            // ->middleware('permission:read invoice')
            ;
            Route::get('/{invoice}/preview', "InvoiceController@printPreview")->name('preview');
            Route::get('/history', 'InvoiceController@index')->name('history')
            // ->middleware('permission:read invoice')
            ;
            Route::get('/datatable', 'InvoiceController@datatable')->name('datatable')
            // ->middleware('permission:read invoice')
            ;
            Route::get('/{invoice}/delete', 'InvoiceController@delete')->name('delete')
            // ->middleware('permission:delete invoice')
            ;
        });
    });
});

Route::prefix('/client')->name('client.')->group(function () {
    Route::namespace('Client')->group(function () {
        Route::namespace('Auth')->group(function () {

            //Login Routes
            Route::get('/login', 'LoginController@showLoginForm')->name('login');
            Route::post('/login', 'LoginController@login');
            Route::post('/logout', 'LoginController@logout')->name('logout');
            // Forgot Password Routes
            Route::get('/password/update', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            // Reset Password Routes
            Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.update');
            Route::get('/password/confirm', 'ConfirmPasswordController@showConfirmForm')->name('password.confirm-request');
            Route::post('password/confirm', 'ConfirmPasswordController@confirm')->name('password.confirm');

        });

        Route::group(['middleware' => ['auth:client']], function () {

            Route::group(['middleware' => ['watch_dog']], function () {

            });
        });
    });

    Route::group(['middleware' => ['auth:client']], function () {
        // Client Home
        Route::get('/home', 'HomeController@index')->name('home');

        Route::group(['middleware' => ['watch_dog']], function () {
            // Change Password Routes
            Route::get('/password/change', 'ClientController@showPasswordForm')->name('password.change.request')->middleware('password.confirm:client.password.confirm-request');
            Route::post('/password/change', 'ClientController@changePassword')->name('password.change');

            // Client profile
            Route::get('/profile', 'ClientController@profile')->name('profile');

            // Client IPs
            Route::get('/ips', "IpController@clientIps")->name('get-ips');

            // Client Report Routes
            Route::get('/calls', "ReportController@ClientCallsSearchPanel")->name('calls-summary.panel');
            Route::get('/calls/summary', "ReportController@ClientCallsSummary")->name('calls-summary');
            Route::get('/calls/export', "ReportController@exportClientCallsSummary")->name('calls-summary.export');
            Route::get('/payments', 'ClientController@showPaymentForm')->name('payments.panel');
            Route::post('/payments/history', 'ClientController@paymentHistory')->name('payments.history');
            Route::get('/report', 'ReportController@clientReportSearchPanel')->name('report');
            Route::get('/report/summary', 'ReportController@clientCallsReport')->name('report.summary');
            Route::get('/report/export', 'ReportController@exportClientCallsReport')->name('report.export');
        });
    });



});
