<?php

use App\Http\Controllers\StaffController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\manager\ManagerController;
use App\Http\Controllers\menu\MenuController;
use App\Http\Controllers\client\ClientController;
use App\Http\Controllers\qc\QcController;
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

Route::get('/panel-admin', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return app(App\Http\Controllers\SesiController::class)->index();
});

Route::middleware(['guest'])->group(function () {
    Route::get('/',[ClientController::class, 'index']);

    Route::get('/panel-admin', [SesiController::class, 'index'])->name('login');
    Route::post('/panel-admin',[SesiController::class, 'login']);
});

Route::get('/error',[SesiController::class, 'error']);

Route::middleware(['UserAkses:manager,super_admin,admin'])->group(function() {

    //DASHBOARD MANAGER
    Route::get('/manager',    [ManagerController::class, 'index']);
    Route::get('/super_admin',[ManagerController::class, 'index']);
    Route::get('/admin',      [ManagerController::class, 'index']);

});

//PRWAPU
Route::middleware(['UserAkses:sales,admin,super_admin,manager'])->group(function() {
    Route::get('/menu/getjenis',                                    [MenuController::class, 'getjenis'])->name('menu/getjenis');
    Route::post('/menu/getjenis',                                   [MenuController::class, 'getjenis'])->name('menu/getjenis');
    Route::get('/menu/getvendor',                                   [MenuController::class, 'getvendor'])->name('menu/getvendor');
    Route::post('/menu/getvendor',                                  [MenuController::class, 'getvendor'])->name('menu/getvendor');
    Route::get('/menu',                                             [MenuController::class, 'index']);
    Route::get('/menu',                                             [MenuController::class, 'index']);
    Route::get('/menu/datatable',                                   [MenuController::class, 'datatable'])->name('menu/datatable');
    Route::post('/menu/datatable',                                  [MenuController::class, 'datatable'])->name('create');
    Route::get('/menu/datatablesharing',                            [MenuController::class, 'datatablesharing'])->name('menu/datatablesharing');
    Route::post('/menu/datatablesharing',                           [MenuController::class, 'datatablesharing'])->name('create');
    Route::get('/menu/create',                                      [MenuController::class, 'create'])->name('create');
    Route::post('/menu/create',                                     [MenuController::class, 'create'])->name('create');
    Route::get('/menu/createvendor',                                [MenuController::class, 'createvendor'])->name('createvendor');
    Route::post('/menu/createvendor',                               [MenuController::class, 'createvendor'])->name('createvendor');
    Route::get('/menu/update/{id}',                                 [MenuController::class, 'update'])->name('update');
    Route::post('/menu/update/{id}',                                [MenuController::class, 'update'])->name('update');
    Route::get('/menu/delete/{id}',                                 [MenuController::class, 'delete'])->name('delete');
    Route::post('/menu/delete/{id}',                                [MenuController::class, 'delete'])->name('delete');
    Route::get('/menu/generate-nomor-pr',                           [MenuController::class, 'generateNomorPR'])->name('menu.generate_nomor_pr');
    Route::get('/menu/detail_data_prwapu',                          [MenuController::class, 'detail_data_prwapu']);
    Route::get('/menu/detail_data_swasta',                          [MenuController::class, 'detail_data_swasta']);
    Route::get('/menu/detail_data_non_ppn',                         [MenuController::class, 'detail_data_nonppn']);
    Route::get('/menu/datatabledetail',                             [MenuController::class, 'datatabledetail'])->name('menu/datatabledetail');
    Route::post('/menu/datatabledetail',                            [MenuController::class, 'datatabledetail'])->name('menu/datatabledetail');
    Route::get('/menu/detailCreate',                                [MenuController::class, 'detailCreate'])->name('detailCreate');
    Route::post('/menu/detailCreate',                               [MenuController::class, 'detailCreate'])->name('detailCreate');
    Route::get('/menu/datatablecogs',                               [MenuController::class, 'datatabledetailcogs'])->name('menu/datatablecogs');
    Route::post('/menu/datatablecogs',                              [MenuController::class, 'datatabledetailcogs'])->name('create');
    Route::get('/menu/deletedetail/{id}',                           [MenuController::class, 'deletedetail'])->name('deletedetail');
    Route::post('/menu/deletedetail/{id}',                          [MenuController::class, 'deletedetail'])->name('deletedetail');
    Route::get('/menu/createcogs',                                  [MenuController::class, 'createCogs'])->name('createcogs');
    Route::post('/menu/createcogs',                                 [MenuController::class, 'createCogs'])->name('createcogs');

    Route::post('/menu/updateTotalPpn',                             [MenuController::class, 'updateTotalPpn'])->name('menu.updateTotalPpn');
    Route::post('/menu/updateTotalPO',                              [MenuController::class, 'updateTotalPO'])->name('menu.updateTotalPO');
    Route::post('/menu/createsharingprovit',                        [MenuController::class, 'createsharingprovit'])->name('menu.createsharingprovit');
    Route::post('/menu/updateincentive',                            [MenuController::class, 'updateIncentive'])->name('menu.updateincentive');
    Route::post('/menu/updateValidasiPayment',                      [MenuController::class, 'updateValidasiPayment'])->name('menu/updateValidasiPayment');
    Route::post('/menu/total_cogs',                                 [MenuController::class, 'getTotalCogs']);

    Route::get('/menu/detailUpdate/{id}',                           [MenuController::class, 'detailUpdate'])->name('menu.detailUpdate');

    Route::get('/menu/detailUpdateCogs/{id}',                       [MenuController::class, 'detailUpdateCogs'])->name('menu/detailUpdateCogs');
    Route::post('/menu/detailUpdateCogs/{id}',                      [MenuController::class, 'detailUpdateCogs'])->name('menu/detailUpdateCogs');

    Route::post('/menu/detailUpdate',                               [MenuController::class, 'detailUpdate'])->name('menu.detailUpdate');
    Route::get('/vendor/search',                                    [MenuController::class, 'search'])->name('vendor.search');

});

Route::get('/logout',                        [SesiController::class, 'logout']);


