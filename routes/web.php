<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Authentication\AuthenticationController@redirect_login');

Route::prefix('app')->middleware('admin')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard.index');
    Route::get('/dashboard/demand', 'DashboardController@demand')->name('dashboard.demand');
    Route::get('/dashboard/cashier', 'DashboardController@cashier_performance')->name('dashboard.cashier_performance');
    Route::get('/dashboard/accumulate', 'DashboardController@accumulation')->name('dashboard.accumulation');

    Route::resource('/items', 'MasterData\ItemController');
    Route::get('/items/get/ajax', 'MasterData\ItemController@data_item_page')->name('items.ajax');
    Route::post('/items/filter', 'MasterData\ItemController@filter_item')->name('items.filter-item');
    Route::post('/items/import', 'MasterData\ItemController@import_item')->name('items.import-item');
    Route::get('/items/data/export', 'MasterData\ItemController@export_item')->name('items.export-item');
    
    Route::resource('/storages', 'Storage\StorageController');
    Route::get('/storages/dept/{dept}', 'Storage\StorageController@filter_by_dept')->name('storages.filter_by_dept');
    Route::get('/storages/item/utama', 'Storage\StorageController@storage_utama')->name('storages.utama');
    Route::get('/storages/item/gudang', 'Storage\StorageController@storage_gudang')->name('storages.gudang');
    Route::get('/storages/item/ecommerce', 'Storage\StorageController@storage_ecommerce')->name('storages.ecommerce');
    Route::get('/storages/item/opname', 'Storage\StorageController@stock_opname')->name('storages.opname');

    // endpoint for ajax call opname
    Route::get('/storages/item/opname/filter', 'Storage\StorageController@filter_opname')->name('storages.filter_opname');
    Route::post('/storages/item/opname/export', 'Storage\StorageController@export_opname')->name('storages.export_opname');

    Route::resource('/records', 'Storage\RecordItemController');
    Route::get('/records/item/masuk', 'Storage\RecordItemController@item_masuk')->name('records.masuk');
    Route::get('/records/item/keluar', 'Storage\RecordItemController@item_keluar')->name('records.keluar');
    /** transaction history */
    Route::get('/records/item/transaction/online', 'Storage\RecordItemController@online_transaction_history')->name('records.online_transaction_history');
    Route::get('/records/item/transaction/offline', 'Storage\RecordItemController@offline_transaction_history')->name('records.offline_transaction_history');
    Route::get('/records/item/transaction/detail/{transaction_id}/{dept}', 'Storage\RecordItemController@detail_transaction_history')->name('records.detail_transaction_history');
    /** transfer item */
    Route::post('/records/item/transfer', 'Storage\RecordItemController@transfer_item')->name('records.transfer');
    /** get ajax request */
    Route::get('/records/item/transaction/history/{dept}', 'Storage\RecordItemController@get_transaction_data')->name('records.get_transaction_data');
    Route::get('/records/item/transaction/filter/{dept}/{from}/{to}', 'Storage\RecordItemController@get_transaction_data_sorted')->name('records.get_transaction_data_sorted');

    // cashier
    Route::get('/cashier/history', 'Activity\CashierController@cashier_history')->name('cashier.history');
    Route::get('/cashier/history/print', 'Activity\CashierController@print_cashier_history')->name('cashier.print_history');
    Route::get('/cashier', 'Activity\CashierController@index')->name('cashier.index');
    Route::get('/cashier/ecommerce', 'Activity\CashierController@cashier_ecommerce')->name('cashier.cashier_ecommerce');

    // Access Management
    Route::get('/access', 'Admin\UserManagementController@index')->name('access.index');
    Route::post('/access/user', 'Admin\UserManagementController@invite_user')->name('access.invite_user');
    Route::post('/access/role', 'Admin\UserManagementController@create_role')->name('access.create_role');
    Route::post('/access/permission', 'Admin\UserManagementController@add_permission')->name('access.add_permission');
    Route::put('/access/role/assign/{user}', 'Admin\UserManagementController@assign_role')->name('access.assign_role');
    Route::put('/access/permission/assign/{role}', 'Admin\UserManagementController@assign_permission')->name('access.assign_permission');
    
    Route::put('/permissions/{permission}', 'Admin\UserManagementController@update_permission')->name('permissions.update');

    Route::put('/roles/{role}', 'Admin\UserManagementController@update_role')->name('roles.update');


    Route::group(['middleware' => ['role:super_admin|root']], function () {
        // data pendukung
        Route::resource('/supports', 'MasterData\SecondaryDataController')->only(['index']);
        Route::resource('/brands', 'MasterData\BrandController')->only(['store', 'update', 'destroy']);
        Route::resource('/units', 'MasterData\UnitController')->only(['store', 'update', 'destroy']);
        Route::resource('/categories', 'MasterData\CategoryController')->only(['store', 'update', 'destroy']);

        // stake Holders
        Route::resource('/entities', 'Relation\StakeHolderController');

        // barcode
        Route::get('/barcodes', 'Admin\BarcodeGenerator@index')->name('barcodes.index');
        Route::post('/barcodes/generate', 'Admin\BarcodeGenerator@generate')->name('barcodes.generate');
        Route::post('/barcodes/print', 'Admin\BarcodeGenerator@print_barcode')->name('barcodes.print_barcode');

        // omset
        Route::get('/omsets', 'Admin\OmsetController@index')->name('omsets.index');
        Route::get('/omsets/calculate', 'Admin\OmsetController@calculate_omset')->name('omsets.calculate_omset');
        // omset ajax export
        Route::post('/omsets/calculate/export', 'Admin\OmsetController@export_omset')->name('omsets.export_omset');

        // Discount
        Route::get('/discounts/customer', 'Admin\DiscountController@discount_customer')->name('discounts.customer');
        Route::get('/discounts/item', 'Admin\DiscountController@discount_item')->name('discounts.item');
        Route::post('/discounts/{discount_id}/occurences', 'Admin\DiscountController@discount_occurences')->name('discounts.occurences');
        /** discount using ajax */
        Route::post('/discounts/customer', 'Admin\DiscountController@store_discount_customer');
        Route::post('/discounts/item', 'Admin\DiscountController@store_discount_item');
        Route::put('/discounts/customer/{discount_customer_id}', 'Admin\DiscountController@update_discount_customer')->name('discounts.update_discount_customer');
        Route::put('/discounts/item/{discount_item_id}', 'Admin\DiscountController@update_discount_item')->name('discounts.update_discount_item');
        Route::delete('/discounts/customer/{discount_customer_id}', 'Admin\DiscountController@delete_discount_customer')->name('discounts.delete_discount_customer');
        Route::delete('/discounts/item/{discount_item_id}', 'Admin\DiscountController@delete_discount_item')->name('discounts.delete_discount_item');
        Route::get('/discounts/customer/{stake_holder_id}', 'Admin\DiscountController@get_customer_discount');

        // Item grosir
        Route::resource('/grosirs', 'Activity\GrosirItemController');
        Route::get('/grosirs/item/lists', 'Activity\GrosirItemController@get_grosir_data'); //ajax request

        // activity
        Route::resource('/orders', 'Activity\OrderController');
        Route::get('/orders/history/list', 'Activity\OrderController@history_order')->name('orders.history_order');
        Route::post('/orders/accept/{order}', 'Activity\OrderController@accept_item')->name('orders.accept_item');
        Route::post('/orders/return/{order}', 'Activity\OrderController@return_item')->name('orders.return_item');
    });

    // payments method&type
    Route::post('payments/method', 'MasterData\PaymentController@store_payment_method')->name('payments.payment-method.store');
    Route::put('payments/method/{payment_method_id}', 'MasterData\PaymentController@update_payment_method')->name('payments.payment-method.update');
    Route::delete('payments/method/{payment_method_id}', 'MasterData\PaymentController@delete_payment_method')->name('payments.payment-method.destroy');
    Route::post('payments/type', 'MasterData\PaymentController@store_payment_type')->name('payments.payment-type.store');
    Route::put('payments/type/{payment_type_id}', 'MasterData\PaymentController@update_payment_type')->name('payments.payment-type.update');
    Route::delete('payments/type/{payment_type_id}', 'MasterData\PaymentController@delete_payment_type')->name('payments.payment-type.destroy');
    /** get payment method using ajax */
    Route::get('/payments/{payment_method_id}', 'MasterData\PaymentController@get_payment_detail');

    // Warn Notification
    Route::resource('/notifications', 'Activity\StockWarnNotificationController');
    Route::get('/notifications/page/list', 'Activity\StockWarnNotificationController@notification_page')->name('notifications.page');

    // change user credentials
    Route::post('/user/update', 'Authentication\AuthenticationController@change_user_credentials');
});

Route::get('/login', 'Authentication\AuthenticationController@login_page')->name('page.login');
Route::post('/login', 'Authentication\AuthenticationController@process_login')->name('process.login');
Route::get('/logout', 'Authentication\AuthenticationController@logout')->name('logout');

Route::get('/cashier/check', 'Activity\CashierController@check_item');
Route::post('/cashier/store', 'Activity\CashierController@store_transaction');