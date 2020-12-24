<?php

use Illuminate\Support\Facades\Route;

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
})->name('home');

Route::get('/leads', [\App\Http\Controllers\LeadsController::class, 'show'])->name('leads');
Route::get('/leads/create', [\App\Http\Controllers\LeadsController::class, 'createLead'])->name('leads.create');
Route::post('/leads/save', [\App\Http\Controllers\LeadsController::class, 'saveLead'])->name('leads.save');
Route::get('/companies/create',
    [\App\Http\Controllers\LeadsController::class, 'createCompany'])->name('companies.create');
Route::post('/companies/save', [\App\Http\Controllers\LeadsController::class, 'saveCompany'])->name('companies.save');
Route::get('/contacts/create',
    [\App\Http\Controllers\LeadsController::class, 'createContact'])->name('contacts.create');
Route::post('/contacts/save', [\App\Http\Controllers\LeadsController::class, 'saveContact'])->name('contacts.save');
Route::get('/notes/create', [\App\Http\Controllers\LeadsController::class, 'createNote'])->name('notes.create');
Route::post('/notes/save', [\App\Http\Controllers\LeadsController::class, 'saveNote'])->name('notes.save');
Route::get('/tasks/create', [\App\Http\Controllers\LeadsController::class, 'createTask'])->name('tasks.create');
Route::post('/tasks/save', [\App\Http\Controllers\LeadsController::class, 'saveTask'])->name('tasks.save');
Route::get('/process', [\App\Http\Controllers\LeadsController::class, 'processTaskPoints'])->name('process');
