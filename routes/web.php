<?php

use Illuminate\Support\Facades\Route;


Route::resource('/items', 'MasterData\ItemController');
Route::post('/items/filter', 'MasterData\ItemController@filter_item')->name('items.filter-item');
Route::resource('/supports', 'MasterData\SecondaryDataController')->only(['index']);
Route::resource('/brands', 'MasterData\BrandController')->only(['store', 'update', 'destroy']);
Route::resource('/units', 'MasterData\UnitController')->only(['store', 'update', 'destroy']);
Route::resource('/categories', 'MasterData\CategoryController')->only(['store', 'update', 'destroy']);
Route::resource('/storages', 'Storage\StorageController');
Route::get('/storages/dept/{dept}', 'Storage\StorageController@filter_by_dept')->name('storages.filter_by_dept');
Route::get('/storages/item/utama', 'Storage\StorageController@storage_utama')->name('storages.utama');
Route::get('/storages/item/gudang', 'Storage\StorageController@storage_gudang')->name('storages.gudang');
Route::get('/storages/item/opname', 'Storage\StorageController@stock_opname')->name('storages.opname');

Route::resource('/records', 'Storage\RecordItemController');
Route::get('/records/item/masuk', 'Storage\RecordItemController@item_masuk')->name('records.masuk');
Route::get('/records/item/keluar', 'Storage\RecordItemController@item_keluar')->name('records.keluar');
Route::post('/records/item/transfer', 'Storage\RecordItemController@transfer_item')->name('records.transfer');

// activity
Route::resource('/orders', 'Activity\OrderController');