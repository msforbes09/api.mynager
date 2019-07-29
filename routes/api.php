<?php

Route::post('/login', 'AuthController@login');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

Route::middleware(['auth:api'])->group(function() {
    Route::post('/logout', 'AuthController@logout');
    Route::get('/summary', 'SummaryController@index');

    Route::post('/income', 'IncomeController@index');
    Route::post('/income/store', 'IncomeController@store');
    Route::get('/income/{income}', 'IncomeController@show');

    Route::post('/expense', 'ExpenseController@index');
    Route::post('/expense/store', 'ExpenseController@store');
    Route::get('/expense/date/{date}', 'ExpenseController@daily');
    Route::post('/expense/search', 'ExpenseController@search');
    Route::get('/expense/{expense}', 'ExpenseController@show');
});
