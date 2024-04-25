<?php

use Illuminate\Support\Facades\Route;

Route::post('transactions/start', 'TransactionController@startTransaction');
Route::post('transactions/commit/{id}', 'TransactionController@commitTransaction');
Route::post('transactions/rollback/{id}', 'TransactionController@rollbackTransaction');