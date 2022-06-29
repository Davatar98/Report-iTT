<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PhoneVerificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

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

Route::get('/',[App\Http\Controllers\WelcomeController::class,'index'])->middleware('guest');


Auth::routes(['verify'=>true]);

//Phone verification routes
Route::get('phoneverify', [PhoneVerificationController::class, 'index'])->name('phone.index');
Route::post('phoneverify', [PhoneVerificationController::class, 'store'])->name('phone.post');
Route::get('2fa/reset', [PhoneVerificationController::class, 'resend'])->name('phone.resend');

//Home page
Route::get('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('report.index')->middleware('auth');

//My Reports Page(PUB-class user)
Route::get('/reportlist',[UserController::class,'index'])->name('reportlist');

//My Reports Page(SP-class user)
Route::get('/adminreportlist',[AdminController::class,'index'])->name('adminreportlist');

//Creates resource routes
Route::resource('report', ReportController::class);
//Ajax request to server to allow the population of the dropdown menu
Route::get('report/create/getfaults/{id}',[ReportController::class,'getFaultNames']);

Route::get('leafletTTEC',[ReportController::class,'getCoordinatesTTEC']);//route to fetch TTEC marker details for leaflet
Route::get('leafletWASA',[ReportController::class,'getCoordinatesWASA']);//route to fetch WASA marker details for leaflet
Route::get('leafletTSTT',[ReportController::class,'getCoordinatesTSTT']);//route to fetch TSTT marker details for leaflet
//Initially used to vote on the created report however, this was removed and replaced with the laravel livewire implementation.
// This route, along with the controller method is no longer needed.
Route::get('report/{report_id}/vote{vote}',[ReportController::class,'vote'])->name('report.vote');
//Shows the report details
Route::get('/report/view/{id}',[ReportController::class,'show'])->name('report.view');
//Used to update the report status
Route::post('status', [ReportController::class, 'updateStatus'])->name('report.status');
//Used to display statistics
Route::get('statistics',[AdminController::class,'statistics'])->name('admin.statistics');
//Route::get('getData',[AdminController::class,'getReportData']);
Route::get('/export',[ReportController::class,'export']); //Testing route for some code.

//User Profile Routes.
Route::get('/profile',[ProfileController::class,'edit'])->name('profile.index');
Route::post('/profile-update',[ProfileController::class,'update'])->name('profile.update');
//Route::resource('profile', ProfileController::class);


