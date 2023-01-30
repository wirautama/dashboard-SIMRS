<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

Route::get('/', [AuthController::class, 'index'])->name('auth.index');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::group(['middleware' => ['web','auth']  ], function () {

    Route::group(['namespace' => 'App\Http\Controllers\Management'], function () {

        Route::get('/management/home', 'HomeController@index')->name('manager.home');

        Route::get('/management/services', 'ServicesController@index')->name('manager.services');

        // Route::get('/management/pharmacy', 'PharmacyController@index')->name('manager.pharmacy');

        // Route::get('/management/laborat', 'LaboratController@index')->name('manager.laborat');



        //Radiologi
        Route::group(['prefix' => 'management/radiology'], function () {
            Route::get('', 'RadiologyController@index')->name('manager.radiology.home');
            Route::get('tindakan', 'RadiologyController@getTindakanRadiologi')->name('manager.radiology.list');
        });    
        
        //Laborat
        Route::group(['prefix' => 'management/laborat'], function () {
            Route::get('', 'LaboratController@index')->name('manager.laborat.home');
            Route::get('tindakan', 'LaboratController@getTindakanLaborat')->name('manager.laborat.list');
        });    
        
        //Obat Kronis
        Route::group(['prefix' => 'management/obatkronis'], function () {
            Route::get('', 'ObatKronisController@index')->name('manager.obatkronis.home');
            Route::get('obatkronis', 'ObatKronisController@getObatKronis')->name('manager.obatkronis.list');
        });    
        
        //Obat Non Kronis
        Route::group(['prefix' => 'management/nonkronis'], function () {
            Route::get('', 'NonKronisController@index')->name('manager.nonkronis.home');
            Route::get('nonkronis', 'NonKronisController@getNonKronis')->name('manager.nonkronis.list');
        });    
        
        //Obat Bebas
        Route::group(['prefix' => 'management/obatbebas'], function () {
            Route::get('', 'PharmacyController@index')->name('manager.obatbebas.home');
            Route::get('obatbebas', 'PharmacyController@getObatBebas')->name('manager.obatbebas.list');
        });    

        
        //Medical Record
        Route::group(['prefix' => 'management/medrec'], function () {
            Route::get('', 'MedicalRecordController@index')->name('manager.medrec');
            Route::get('getalos/{start}/{end}', 'MedicalRecordController@getAlos')->name('manager.medrec.alos');
            Route::get('getbor/{start}/{end}', 'MedicalRecordController@getBor')->name('manager.medrec.bor');
            Route::get('getdoctor', 'MedicalRecordController@getDoctor')->name('manager.medrec.doctor');
            Route::get('getpatient/{start}/{end}', 'MedicalRecordController@getPatient')->name('manager.medrec.patient');
            Route::get('getReadmittedRate/{start}/{end}', 'MedicalRecordController@getReadmittedRate')->name('manager.medrec.readmitted');
            Route::get('getAdmittedRateRalan/{start}/{end}', 'MedicalRecordController@getAdmittedRateRalan')->name('manager.medrec.admittedrateralan');
            Route::get('getBestDiagnose/{start}/{end}', 'MedicalRecordController@getBestDiagnose')->name('manager.medrec.bestdiagnose');
            Route::get('getInOut/{start}/{end}', 'MedicalRecordController@getInOut')->name('manager.medrec.inout');
            Route::get('getAppointments', 'MedicalRecordController@getAppointments')->name('manager.medrec.appointments');

        });


    });


    Route::group(['namespace' => 'App\Http\Controllers\Pendapatan'], function () {

        //Tindakan
        Route::group(['prefix' => 'pendapatan/ralan'], function () {
            Route::get('', 'TindakanRalanController@index')->name('pendapatan.ralan.home');
            Route::get('tindakan', 'TindakanRalanController@getTindakanRalan')->name('pendapatan.ralan.list');


        });

        //Tindakan Rawat Inap
        Route::group(['prefix' => 'pendapatan/ranap'], function () {
            Route::get('', 'TindakanRanapController@index')->name('pendapatan.ranap.home');
            Route::get('tindakan', 'TindakanRanapController@getTindakanRanap')->name('pendapatan.ranap.list');


        });

        //Anestesi
        Route::group(['prefix' => 'pendapatan/anestesi'], function () {
            Route::get('', 'TindakanAnestesiController@index')->name('pendapatan.anestesi.home');
            Route::get('tindakan', 'TindakanAnestesiController@getTindakanAnestesi')->name('pendapatan.anestesi.list');


        });
        
        //Grouper Ralan
        Route::group(['prefix' => 'pendapatan/grouperralan'], function () {
            Route::get('', 'GrouperRalanController@index')->name('pendapatan.grouperralan.home');
            Route::get('grouperralan', 'GrouperRalanController@getGrouperRalan')->name('pendapatan.grouperralan.list');


        });
        //Grouper Ranap
        Route::group(['prefix' => 'pendapatan/grouperranap'], function () {
            Route::get('', 'GrouperRanapController@index')->name('pendapatan.grouperranap.home');
            Route::get('grouperranap', 'GrouperRanapController@getGrouperRanap')->name('pendapatan.grouperranap.list');


        });

        //Operator 1
        Route::group(['prefix' => 'pendapatan/operator'], function () {
            Route::get('', 'TindakanOperatorController@index')->name('pendapatan.operator.home');
            Route::get('tindakan', 'TindakanOperatorController@getTindakanOperator')->name('pendapatan.operator.list');


        });

        //Obat Ralan
        Route::group(['prefix' => 'pendapatan/obatralan'], function () {
            Route::get('', 'ObatRalanController@index')->name('pendapatan.obatralan.home');
            Route::get('obat', 'ObatRalanController@getObatRalan')->name('pendapatan.obatralan.list');


        });
        
        //Obat Inap
        Route::group(['prefix' => 'pendapatan/obatinap'], function () {
            Route::get('', 'ObatInapController@index')->name('pendapatan.obatinap.home');
            Route::get('obat', 'ObatInapController@getObatInap')->name('pendapatan.obatinap.list');


        });

    });

    Route::group(['namespace' => 'App\Http\Controllers\Util'], function () {

        //Tindakan
        Route::group(['prefix' => 'util'], function () {
            Route::get('doctor', 'UtilController@getDokter')->name('util.doctor');
        });


    });

});