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

use App\Task;
use Illuminate\Http\Request;

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
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/tasks')
            ->withInput()
            ->withErrors($validator);
    }
    
    $task = new Task;
    $task->name = $request->name;
    $task->save();

    return redirect('/tasks');
});

/**
 * Delete An Existing Task
 */
Route::delete('/task/{id}', function($id) {
    //
});
