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


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/parse', "ParseLogController@parse");
Route::get('/get', "ParseLogController@getPrefix");
Route::get('/test', "ReportController@test");
Route::get('/testreport', "ParseLogController@testReport");



Route::group(['middleware' => ['auth']], function () {

    // ACL Routes
    Route::group(['middleware' => ['role:Super Admin']], function () {

        Route::resource('users', "UserController");
        Route::get('/getUser', "UserController@getUser")->name('getUser');
        Route::get('/users/delete/{user}', "UserController@destroy");
        Route::resource('roles', "RoleController");
        Route::get('/getRoles', "RoleController@getRoles")->name('getRoles');
        Route::get('/getRolesName', 'RoleController@getRolesName')->name('getRolesName');
        Route::post('assignRole', "RoleController@assignRole")->name('assignRole');
        Route::get('/getUsers', 'RoleController@getUsers')->name('getUsers');
        Route::resource('permissions', "PermissionController");
        Route::get('/getPermissions', "PermissionController@getPermissions")->name('getPermissions');
        Route::post('/assignPermissions', "PermissionController@assignPermissions")->name('assignPermissions');
        Route::post('/getUserPermissions', "PermissionController@getUserPermissions")->name('getUserPermissions');
        Route::post('/getRolePermissions', "PermissionController@getRolePermissions")->name('getRolePermissions');
    });

    // Currency Routes
    Route::get('/currencies', 'CurrencyController@index')->name('currencies.index');
    Route::post('/currencies', 'CurrencyController@store')->name('currencies.store');
    Route::put('/currencies/{currency}', 'CurrencyController@update')->name('currencies.update');
    Route::delete('/currencies/{currency}', 'CurrencyController@destroy')->name('currencies.delete');
    Route::get('/getCurrencies', "CurrencyController@getCurrencies")->name('getCurrencies');
    Route::post('/getCurrency', 'CurrencyController@show')->name('getCurrency');

    // Tariff Routes
    Route::get('/tariffnames', 'TariffNameController@index')->name('tariffname.index');
    Route::post('/tariffnames', 'TariffNameController@store')->name('tariffname.store');
    Route::post('/tariffname/show', 'TariffNameController@show')->name('tariffname.show');
    Route::get('/tariffnames/all', 'TariffNameController@tariffNames')->name('tariffnames');
    Route::put('/tariffnames/{tariffName}', 'TariffNameController@update')->name('tariffname.update');
    Route::get('/tariffnames/datatable', 'TariffNameController@dataTable')->name('tariffnames.datatable');
    Route::get('/getCurrenciesName', 'TariffNameController@getCurrenciesName')->name('getCurrenciesName');
    Route::delete('/tariffnames/{tariffName}', 'TariffNameController@destroy')->name('tariffnames.delete');

    // Tariff Rates Routes
    Route::get('/tariffnames/{tariffname}/rates', 'RateController@index')->name('rate.index');
    Route::post('tariffnames/rates', "RateController@store")->name('rate.store');
    Route::put('tariffnames/rates/{rate}', 'RateController@update')->name('rate.update');
    Route::delete('tariffnames/rates/{rate}', 'RateController@destroy')->name('rate.delete');
    Route::get('getTariffRates/{tariffname}', 'RateController@getRatesTable')->name('getTariffRates');
    Route::post('/getRateDetails', "RateController@getRateDetails")->name('getRateDetails');
    Route::get('tariffnames/{tariffname}/rates/export', 'RateController@export')->name('rate.export');
    Route::post('tariffnames/rate/import/', 'RateController@import')->name('rate.import');
    Route::get('tariffnames/rate/download/', 'RateController@download')->name('rate.download');

    // Gateway Routes
    Route::get('/gateways', "GatewayController@index")->name('gateway.index');
    Route::get('/gateways/all', 'GatewayController@gateways')->name('gateways');
    Route::post('/gateways/show', 'GatewayController@show')->name('gateway.show');
    Route::post('/gateways/store', "GatewayController@store")->name('gateway.store');
    Route::put('/gateways/{gateway}', 'GatewayController@update')->name('gateway.update');
    Route::post('/gateways/payments', 'GatewayController@payment')->name('gateway.payments');
    Route::delete('/gateways/{gateway}', 'GatewayController@destroy')->name('gateway.delete');
    Route::get('/gateways/datatable', 'GatewayController@dataTable')->name('gateways.datatable');
    Route::post('/gateways/payment/store', 'GatewayController@paymentStore')->name('gateway.payment.store');


    // General Queries
    Route::get('/getCountries', "Homecontroller@getCountries")->name('countries');
    Route::get('/payment-types', 'Homecontroller@getPaymentTypes')->name('payment-types');

    // Clients Routes
    Route::get('/clients', "ClientController@index")->name('client.index');
    Route::get('/clients/all', "ClientController@clients")->name('clients');
    Route::post('/clients/show', "ClientController@show")->name('client.show');
    Route::post('/clients/store', "ClientController@store")->name('client.store');
    Route::put('/clients/{client}', "ClientController@update")->name('client.update');
    Route::post('/clients/payments', 'ClientController@payment')->name('client.payments');
    Route::delete('/clients/{client}', 'ClientController@destroy')->name('client.delete');
    Route::get('/clients/datatable', "ClientController@dataTable")->name('clients.datatable');
    Route::post('/clients/payment/store', 'ClientController@paymentStore')->name('client.payment.store');

    // IP Routes
    Route::post('/clients/ips', "IpController@store")->name('ip.store');
    Route::post('/clients/ips/show', "IpController@show")->name('ip.show');
    Route::post('/clients/ips/get', "IpController@index")->name('ip.index');
    Route::put('/clients/ips/update', "IpController@update")->name('ip.update');
    Route::delete('/clients/ips/{ip}', "IpController@destroy")->name('ip.delete');
    Route::get('/clients/ips', "IpController@clientsIps")->name('clients.ips');

    // Simulation Routes
    Route::post('/bill/simulate', "ParseLogController@simulate")->name('bill.simulate');
    Route::get('/bill/simulate', "ParseLogController@getSimulate")->name('bill.simulate.panel');

    // CDR Log Routes
    Route::get('/cdrlogs', "CdrLogController@index")->name('cdr.logs');
    Route::get('/cdrlogs/get', "CdrLogController@getCdrLogs")->name('get.cdr.logs');
    Route::get('/cdrlogs/show/{file_name}', "CdrLogController@show")->name('cdr.log.show');
    Route::get('/cdrlogs/reparse/{file_name}', "CdrLogController@reparse")->name('cdr.log.reparse');

    // Reporting Routes
    Route::get('/summary/orig-term-calls', "ReportController@origTermCallsSearchPanel")->name('orig-term-calls.summary.panel');
    Route::get('/summary/orig-term-calls/fetch', "ReportController@origTermSummary")->name('orig-term-calls.summary.fetch');
    Route::get('/summary/orig-term-calls/export', "ReportController@exportOrigTermSummary")->name('orig-term-calls.summary.export');
    Route::get('/summary/success-calls', "ReportController@successCallsSearchPanel")->name('success-calls.summary.panel');
    Route::get('/summary/success-calls/fetch', "ReportController@successCallsSummary")->name('success-calls.summary.fetch');
    Route::get('/summary/success-calls/export', "ReportController@exportSuccessSummary")->name('success-calls.summary.export');
    Route::get('/summary/failed-calls', "ReportController@failedCallsSearchPanel")->name('failed-calls.summary.panel');
    Route::get('/summary/failed-calls/fetch', "ReportController@failedCallsSummary")->name('failed-calls.summary.fetch');
    Route::get('/summary/failed-calls/export', "ReportController@exportFailedSummary")->name('failed-calls.summary.export');
    Route::get('/summary/loss-profit', "ReportController@lossProfitSearchPanel")->name('loss-profit.summary.panel');
    Route::get('/summary/loss-profit/fetch', "ReportController@lossProfitSummary")->name('loss-profit.summary.fetch');
    Route::get('/summary/loss-profit/export', "ReportController@exportlossProfitSummary")->name('loss-profit.summary.export');

});
