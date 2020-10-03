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

Route::get('/', function () {
    return view('welcome');
});

/**
 * Display All Tasks
 */
Route::get('/tasks', function () {
    return view('tasks');
});

/**
 * Add A New Task
 */
Route::post('/task', function(Request $request) {
    //
});

/**
 * Delete An Existing Task
 */
Route::delete('/task/{id}', function($id) {
    //
});
