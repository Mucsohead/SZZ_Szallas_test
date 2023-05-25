<?php

use App\Http\Controllers\CompaniesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Tudj felvinni új céget az adatbázisban

    Route::get('companies', [CompaniesController::class, 'getCompanies']);
    Route::post('companies/new', [CompaniesController::class,'addCompany']);
    Route::patch('companies/{companyId}', [CompaniesController::class,'updateCompany']);

    Route::get('companies/by_activities',[CompaniesController::class, 'getCompaniesByActivities']);
//Tudj lekérni adatokat ID-alapján egy adott cégről (több id-t is be lehet adni)

//Tudj módosítani létező cégről adatokat
