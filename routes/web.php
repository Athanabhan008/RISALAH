<?php

use App\Http\Controllers\StaffController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\booking\BookingController;
use App\Http\Controllers\KategoribarangController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\manager\ManagerController;
use App\Http\Controllers\sound_system\SoundController;
use App\Http\Controllers\manager\BarangController;
use App\Http\Controllers\Report\ReportAbsenController;
use App\Http\Controllers\Report\ReportLaundryController;
use App\Http\Controllers\Report\ReportBookingController;
use App\Http\Controllers\Report\ReportSoundController;
use App\Http\Controllers\Report\ReportBarangController;
use App\Http\Controllers\ManajemenakunController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PO\PoController;
use App\Http\Controllers\DO\DoController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\pr\WapuController;
use App\Http\Controllers\pr\SwastaController;
use App\Http\Controllers\pr\NonppnController;
use App\Http\Controllers\ProfitController as ControllersProfitController;
use App\Http\Controllers\qc\QcController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\profit\ProfitController;
use App\Http\Controllers\omset\OmsetController;
use App\Http\Controllers\StokbarangController;
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

Route::middleware(['guest'])->group(function () {
    Route::get('/',[SesiController::class, 'index'])->name('login');
    Route::post('/',[SesiController::class, 'login']);
});
Route::get('/home', function() {
    return redirect('/manager');
});

Route::get('/error',[SesiController::class, 'error']);

Route::middleware(['UserAkses:sales,teknisi'])->group(function() {


    Route::get('/sales', [StaffController::class, 'index']);
    Route::get('/teknisi',[QcController::class, 'index']);

});

Route::middleware(['UserAkses:manager,super_admin,admin'])->group(function() {

    //DASHBOARD MANAGER
    Route::get('/manager',    [ManagerController::class, 'index']);
    Route::get('/super_admin',[ManagerController::class, 'index']);
    Route::get('/admin',      [ManagerController::class, 'index']);

});

//STAFF
Route::middleware(['UserAkses:super_admin, manager'])->group(function() {
    Route::get('/user',                                             [UserController::class, 'index']);
    Route::get('/user/datatable',                                   [UserController::class, 'datatable'])->name('user/datatable');
    Route::post('/user/datatable',                                  [UserController::class, 'datatable'])->name('user/datatable');
    Route::post('/user/datatable',                                  [UserController::class, 'datatable'])->name('create');
    Route::get('/user/create',                                      [UserController::class, 'create'])->name('create');
    Route::post('/user/create',                                     [UserController::class, 'create'])->name('create');
    Route::get('/user/update/{id}',                                 [UserController::class, 'update'])->name('update');
    Route::post('/user/update/{id}',                                [UserController::class, 'update'])->name('update');
    Route::get('/user/updatepassword/{id}',                         [UserController::class, 'updatepassword'])->name('updatepassword');
    Route::post('/user/updatepassword/{id}',                        [UserController::class, 'updatepassword'])->name('updatepassword');
    Route::get('/user/getshift',                                    [UserController::class, 'getshift'])->name('sound/getshift');
    Route::post('/user/getshift',                                   [UserController::class, 'getshift'])->name('sound/getshift');
});


//Approval
Route::middleware(['UserAkses:manager,admin,super_admin'])->group(function() {
    Route::get('/approval',                                             [ApprovalController::class, 'index']);
    Route::get('/approval/datatable',                                   [ApprovalController::class, 'datatable'])->name('approval/datatable');
    Route::post('/approval/datatable',                                  [ApprovalController::class, 'datatable'])->name('approval/datatable');
    Route::post('/approval/datatable',                                  [ApprovalController::class, 'datatable'])->name('create');
    Route::get('/approval/create',                                      [ApprovalController::class, 'create'])->name('create');
    Route::post('/approval/create',                                     [ApprovalController::class, 'create'])->name('create');
    Route::get('/approval/update/{id}',                                 [ApprovalController::class, 'update'])->name('update');
    Route::post('/approval/update/{id}',                                [ApprovalController::class, 'update'])->name('update');
    Route::get('/approval/updatepassword/{id}',                         [ApprovalController::class, 'updatepassword'])->name('updatepassword');
    Route::post('/approval/updatepassword/{id}',                        [ApprovalController::class, 'updatepassword'])->name('updatepassword');
    Route::get('/approval/getshift',                                    [ApprovalController::class, 'getshift'])->name('sound/getshift');
    Route::post('/approval/getshift',                                   [ApprovalController::class, 'getshift'])->name('sound/getshift');
    Route::post('/approval/setApprove',                                 [ApprovalController::class, 'setApprove'])->name('approval/setApprove');
    Route::get('/approval/cetakpdf',                                    [ApprovalController::class, 'cetakPDF'])->name('approval/cetakpdf');
    Route::post('/approval/cetakpdf',                                   [ApprovalController::class, 'cetakPDF'])->name('approval/cetakpdf');
});

//PRWAPU
Route::middleware(['UserAkses:sales,admin,super_admin,manager'])->group(function() {
    Route::get('/pr_wapu/getSales',                                    [WapuController::class, 'getSales'])->name('pr_wapu/getSales');
    Route::post('/pr_wapu/getSales',                                   [WapuController::class, 'getSales'])->name('pr_wapu/getSales');
    Route::get('/pr_wapu/getvendor',                                   [WapuController::class, 'getvendor'])->name('pr_wapu/getvendor');
    Route::post('/pr_wapu/getvendor',                                  [WapuController::class, 'getvendor'])->name('pr_wapu/getvendor');
    Route::get('/pr_wapu',                                             [WapuController::class, 'index']);
    Route::get('/pr_wapu',                                             [WapuController::class, 'index']);
    Route::get('/pr_wapu/datatable',                                   [WapuController::class, 'datatable'])->name('pr_wapu/datatable');
    Route::post('/pr_wapu/datatable',                                  [WapuController::class, 'datatable'])->name('create');
    Route::get('/pr_wapu/datatablesharing',                            [WapuController::class, 'datatablesharing'])->name('pr_wapu/datatablesharing');
    Route::post('/pr_wapu/datatablesharing',                           [WapuController::class, 'datatablesharing'])->name('create');
    Route::get('/pr_wapu/create',                                      [WapuController::class, 'create'])->name('create');
    Route::post('/pr_wapu/create',                                     [WapuController::class, 'create'])->name('create');
    Route::get('/pr_wapu/createvendor',                                [WapuController::class, 'createvendor'])->name('createvendor');
    Route::post('/pr_wapu/createvendor',                               [WapuController::class, 'createvendor'])->name('createvendor');
    Route::get('/pr_wapu/update/{id}',                                 [WapuController::class, 'update'])->name('update');
    Route::post('/pr_wapu/update/{id}',                                [WapuController::class, 'update'])->name('update');
    Route::get('/pr_wapu/delete/{id}',                                 [WapuController::class, 'delete'])->name('delete');
    Route::post('/pr_wapu/delete/{id}',                                [WapuController::class, 'delete'])->name('delete');
    Route::get('/pr_wapu/generate-nomor-pr',                           [WapuController::class, 'generateNomorPR'])->name('pr_wapu.generate_nomor_pr');
    Route::get('/pr_wapu/detail_data_prwapu',                          [WapuController::class, 'detail_data_prwapu']);
    Route::get('/pr_wapu/detail_data_swasta',                          [WapuController::class, 'detail_data_swasta']);
    Route::get('/pr_wapu/detail_data_non_ppn',                         [WapuController::class, 'detail_data_nonppn']);
    Route::get('/pr_wapu/datatabledetail',                             [WapuController::class, 'datatabledetail'])->name('pr_wapu/datatabledetail');
    Route::post('/pr_wapu/datatabledetail',                            [WapuController::class, 'datatabledetail'])->name('pr_wapu/datatabledetail');
    Route::get('/pr_wapu/detailCreate',                                [WapuController::class, 'detailCreate'])->name('detailCreate');
    Route::post('/pr_wapu/detailCreate',                               [WapuController::class, 'detailCreate'])->name('detailCreate');
    Route::get('/pr_wapu/datatablecogs',                               [WapuController::class, 'datatabledetailcogs'])->name('pr_wapu/datatablecogs');
    Route::post('/pr_wapu/datatablecogs',                              [WapuController::class, 'datatabledetailcogs'])->name('create');
    Route::get('/pr_wapu/deletedetail/{id}',                           [WapuController::class, 'deletedetail'])->name('deletedetail');
    Route::post('/pr_wapu/deletedetail/{id}',                          [WapuController::class, 'deletedetail'])->name('deletedetail');
    Route::get('/pr_wapu/createcogs',                                  [WapuController::class, 'createCogs'])->name('createcogs');
    Route::post('/pr_wapu/createcogs',                                 [WapuController::class, 'createCogs'])->name('createcogs');

    Route::post('/pr_wapu/updateTotalPpn',                             [WapuController::class, 'updateTotalPpn'])->name('pr_wapu.updateTotalPpn');
    Route::post('/pr_wapu/updateTotalPO',                              [WapuController::class, 'updateTotalPO'])->name('pr_wapu.updateTotalPO');
    Route::post('/pr_wapu/createsharingprovit',                        [WapuController::class, 'createsharingprovit'])->name('pr_wapu.createsharingprovit');
    Route::post('/pr_wapu/updateincentive',                            [WapuController::class, 'updateIncentive'])->name('pr_wapu.updateincentive');
    Route::post('/pr_wapu/updateValidasiPayment',                      [WapuController::class, 'updateValidasiPayment'])->name('pr_wapu/updateValidasiPayment');
    Route::post('/pr_wapu/total_cogs',                                 [WapuController::class, 'getTotalCogs']);

    Route::get('/pr_wapu/detailUpdate/{id}',                           [WapuController::class, 'detailUpdate'])->name('pr_wapu.detailUpdate');

    Route::get('/pr_wapu/detailUpdateCogs/{id}',                       [WapuController::class, 'detailUpdateCogs'])->name('pr_wapu/detailUpdateCogs');
    Route::post('/pr_wapu/detailUpdateCogs/{id}',                      [WapuController::class, 'detailUpdateCogs'])->name('pr_wapu/detailUpdateCogs');

    Route::post('/pr_wapu/detailUpdate',                               [WapuController::class, 'detailUpdate'])->name('pr_wapu.detailUpdate');
    Route::get('/vendor/search',                                       [WapuController::class, 'search'])->name('vendor.search');

});

Route::middleware(['UserAkses:admin,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/po_vendor',                                              [PoController::class, 'index']);
    Route::get('/po/datatable',                                           [PoController::class, 'datatable'])->name('po/datatable');
    Route::post('/po/datatable',                                          [PoController::class, 'datatable'])->name('create');
    Route::get('/po/generate-nomor-po',                                   [PoController::class, 'generateNomorPO'])->name('po.generate_nomor_po');
    Route::get('/po/getSales',                                            [PoController::class, 'getSales'])->name('po/getSales');
    Route::post('/po/getSales',                                           [PoController::class, 'getSales'])->name('po/getSales');
    Route::get('/po/getPr',                                              [PoController::class, 'getPr'])->name('po/getPr');
    Route::post('/po/getPr',                                             [PoController::class, 'getPr'])->name('po/getPr');
    Route::get('/po/getVendor',                                              [PoController::class, 'getVendor'])->name('po/getVendor');
    Route::post('/po/getVendor',                                             [PoController::class, 'getVendor'])->name('po/getVendor');
    Route::get('/po/create',                                        [PoController::class, 'create'])->name('create');
    Route::post('/po/create',                                       [PoController::class, 'create'])->name('create');
    Route::get('/po/cetakCV',                                       [PoController::class, 'cetakCV'])->name('po/cetakCV');
    Route::post('/po/cetakCV',                                      [PoController::class, 'cetakCV'])->name('po/cetakCV');
    Route::get('/po/cetakPT',                                       [PoController::class, 'cetakPT'])->name('po/cetakPT');
    Route::post('/po/cetakPT',                                      [PoController::class, 'cetakPT'])->name('po/cetakPT');
});

//DELIVERY ORDER
    Route::middleware(['UserAkses:admin,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/delivery_order',                                                     [DoController::class, 'index']);
    Route::get('/delivery_order/datatable',                                           [DoController::class, 'datatable'])->name('delivery_order/datatable');
    Route::post('/delivery_order/datatable',                                          [DoController::class, 'datatable'])->name('create');
    Route::get('/delivery_order/generate-nomor-do',                                   [DoController::class, 'generateNomorDO'])->name('delivery_order.generate_nomor_do');
    Route::get('/delivery_order/getSales',                                            [DoController::class, 'getSales'])->name('delivery_order/getSales');
    Route::post('/delivery_order/getSales',                                           [DoController::class, 'getSales'])->name('delivery_order/getSales');
    Route::get('/delivery_order/getQc',                                               [DoController::class, 'getQc'])->name('delivery_order/getQc');
    Route::post('/delivery_order/getQc',                                              [DoController::class, 'getQc'])->name('delivery_order/getQc');
    Route::get('/delivery_order/create',                                              [DoController::class, 'create'])->name('create');
    Route::post('/delivery_order/create',                                             [DoController::class, 'create'])->name('create');
    Route::get('/delivery_order/cetakCV',                                             [DoController::class, 'cetakCV'])->name('delivery_order/cetakCV');
    Route::post('/delivery_order/cetakCV',                                            [DoController::class, 'cetakCV'])->name('delivery_order/cetakCV');
    Route::get('/delivery_order/cetakPT',                                             [DoController::class, 'cetakPT'])->name('delivery_order/cetakPT');
    Route::post('/delivery_order/cetakPT',                                            [DoController::class, 'cetakPT'])->name('delivery_order/cetakPT');

});


//INVOICE
Route::middleware(['UserAkses:admin,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/invoice',                                                     [InvoiceController::class, 'index']);
    Route::get('/invoice/datatable',                                           [InvoiceController::class, 'datatable'])->name('invoice/datatable');
    Route::post('/invoice/datatable',                                          [InvoiceController::class, 'datatable'])->name('create');
    Route::get('/invoice/generate-nomor-inv',                                   [InvoiceController::class, 'generateNomorInv'])->name('invoice.generate_nomor_inv');
    Route::get('/invoice/getSales',                                            [InvoiceController::class, 'getSales'])->name('invoice/getSales');
    Route::post('/invoice/getSales',                                           [InvoiceController::class, 'getSales'])->name('invoice/getSales');
    Route::get('/invoice/getPr',                                               [InvoiceController::class, 'getPr'])->name('invoice/getPr');
    Route::post('/invoice/getPr',                                              [InvoiceController::class, 'getPr'])->name('invoice/getPr');
    Route::get('/invoice/create',                                              [InvoiceController::class, 'create'])->name('create');
    Route::post('/invoice/create',                                             [InvoiceController::class, 'create'])->name('create');
    Route::get('/invoice/cetakCV',                                             [InvoiceController::class, 'cetakCV'])->name('invoice/cetakCV');
    Route::post('/invoice/cetakCV',                                            [InvoiceController::class, 'cetakCV'])->name('invoice/cetakCV');
    Route::get('/invoice/cetakPT',                                             [InvoiceController::class, 'cetakPT'])->name('invoice/cetakPT');
    Route::post('/invoice/cetakPT',                                            [InvoiceController::class, 'cetakPT'])->name('invoice/cetakPT');

});


//PROFIT
Route::middleware(['UserAkses:admin,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/profit',                                                     [ProfitController::class, 'index']);
    Route::get('/profit/datatable',                                           [ProfitController::class, 'datatable'])->name('profit/datatable');
    Route::post('/profit/datatable',                                          [ProfitController::class, 'datatable'])->name('create');
    Route::get('/profit/generate-nomor-inv',                                  [ProfitController::class, 'generateNomorInv'])->name('profit.generate_nomor_inv');
    Route::get('/profit/getSales',                                            [ProfitController::class, 'getSales'])->name('profit/getSales');
    Route::post('/profit/getSales',                                           [ProfitController::class, 'getSales'])->name('profit/getSales');
    Route::get('/profit/getPr',                                               [ProfitController::class, 'getPr'])->name('profit/getPr');
    Route::post('/profit/getPr',                                              [ProfitController::class, 'getPr'])->name('profit/getPr');
    Route::get('/profit/create',                                              [ProfitController::class, 'create'])->name('create');
    Route::post('/profit/create',                                             [ProfitController::class, 'create'])->name('create');
    Route::get('/profit/cetakCV',                                             [ProfitController::class, 'cetakCV'])->name('profit/cetakCV');
    Route::post('/profit/cetakCV',                                            [ProfitController::class, 'cetakCV'])->name('profit/cetakCV');
    Route::get('/profit/cetakPT',                                             [ProfitController::class, 'cetakPT'])->name('profit/cetakPT');
    Route::post('/profit/cetakPT',                                            [ProfitController::class, 'cetakPT'])->name('profit/cetakPT');

});


//OMSET
Route::middleware(['UserAkses:admin,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/omset',                                                     [OmsetController::class, 'index']);
    Route::get('/omset/datatable',                                           [OmsetController::class, 'datatable'])->name('omset/datatable');
    Route::post('/omset/datatable',                                          [OmsetController::class, 'datatable'])->name('create');
    Route::get('/omset/generate-nomor-inv',                                  [OmsetController::class, 'generateNomorInv'])->name('omset.generate_nomor_inv');
    Route::get('/omset/getSales',                                            [OmsetController::class, 'getSales'])->name('omset/getSales');
    Route::post('/omset/getSales',                                           [OmsetController::class, 'getSales'])->name('omset/getSales');
    Route::get('/omset/getPr',                                               [OmsetController::class, 'getPr'])->name('omset/getPr');
    Route::post('/omset/getPr',                                              [OmsetController::class, 'getPr'])->name('omset/getPr');
    Route::get('/omset/create',                                              [OmsetController::class, 'create'])->name('create');
    Route::post('/omset/create',                                             [OmsetController::class, 'create'])->name('create');
    Route::get('/omset/cetakpdf',                                            [OmsetController::class, 'cetakPDF'])->name('omset/cetakpdf');
    Route::post('/omset/cetakpdf',                                           [OmsetController::class, 'cetakPDF'])->name('omset/cetakpdf');

});


//QUALITY CONTROL
Route::middleware(['UserAkses:teknisi,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/qc',                                                          [QcController::class, 'index']);
    Route::get('/qc/datatable',                                                [QcController::class, 'datatable'])->name('qc/datatable');
    Route::post('/qc/datatable',                                               [QcController::class, 'datatable'])->name('create');
    Route::get('/qc/create',                                                   [QcController::class, 'create'])->name('create');
    Route::post('/qc/create',                                                  [QcController::class, 'create'])->name('create');
    Route::get('/qc/getSales',                                                 [QcController::class, 'getSales'])->name('qc/getSales');
    Route::post('/qc/getSales',                                                [QcController::class, 'getSales'])->name('qc/getSales');
    Route::get('/qc/getPr',                                                    [QcController::class, 'getPr'])->name('qc/getPr');
    Route::post('/qc/getPr',                                                   [QcController::class, 'getPr'])->name('qc/getPr');
    Route::get('/qc/detail_data_qc',                                           [QcController::class, 'detail_data_qc']);
    Route::get('/qc/datatabledetail',                                          [QcController::class, 'datatabledetail'])->name('qc/datatabledetail');
    Route::post('/qc/datatabledetail',                                         [QcController::class, 'datatabledetail'])->name('qc/datatabledetail');
    Route::get('/qc/getbarang',                                                [QcController::class, 'getBarang'])->name('qc/getbarang');
    Route::post('/qc/getbarang',                                               [QcController::class, 'getBarang'])->name('qc/getbarang');
    Route::post('/qc/detailCreate',                                            [QcController::class, 'detailcreate'])->name('qc/detailCreate');
    Route::post('/qc/detailCreate',                                            [QcController::class, 'detailcreate'])->name('qc/detailCreate');

    // Route::get('/invoice/cetakCV',                                             [InvoiceController::class, 'cetakCV'])->name('invoice/cetakCV');
    // Route::post('/invoice/cetakCV',                                            [InvoiceController::class, 'cetakCV'])->name('invoice/cetakCV');
    // Route::get('/invoice/cetakPT',                                             [InvoiceController::class, 'cetakPT'])->name('invoice/cetakPT');
    // Route::post('/invoice/cetakPT',                                            [InvoiceController::class, 'cetakPT'])->name('invoice/cetakPT');

});

////NON PPN
Route::middleware(['UserAkses:sales,admin,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/non_ppn/getSales',                                    [NonppnController::class, 'getSales'])->name('non_ppn/getSales');
    Route::post('/non_ppn/getSales',                                   [NonppnController::class, 'getSales'])->name('non_ppn/getSales');
    Route::get('/non_ppn/datatable',                                   [NonppnController::class, 'datatable'])->name('non_ppn/datatable');
    Route::post('/non_ppn/datatable',                                  [NonppnController::class, 'datatable'])->name('create');
    Route::get('/non_ppn/create',                                      [NonppnController::class, 'create'])->name('create');
    Route::post('/non_ppn/create',                                     [NonppnController::class, 'create'])->name('create');
    Route::get('/non_ppn/update/{id}',                                 [NonppnController::class, 'update'])->name('update');
    Route::post('/non_ppn/update/{id}',                                [NonppnController::class, 'update'])->name('update');
    Route::get('/non_ppn/delete/{id}',                                 [NonppnController::class, 'delete'])->name('delete');
    Route::post('/non_ppn/delete/{id}',                                [NonppnController::class, 'delete'])->name('delete');
    Route::get('/non_ppn/generate-nomor-pr',                           [NonppnController::class, 'generateNomorPR'])->name('non_ppn.generate_nomor_pr');
    Route::get('/non_ppn/datatabledetail',                             [NonppnController::class, 'datatabledetail'])->name('non_ppn/datatabledetail');
    Route::post('/non_ppn/datatabledetail',                            [NonppnController::class, 'datatabledetail'])->name('non_ppn/datatabledetail');
    Route::get('/non_ppn/detailCreate',                                [NonppnController::class, 'detailCreate'])->name('detailCreate');
    Route::post('/non_ppn/detailCreate',                               [NonppnController::class, 'detailCreate'])->name('detailCreate');
    Route::get('/non_ppn/datatablecogs',                               [NonppnController::class, 'datatabledetailcogs'])->name('non_ppn/datatablecogs');
    Route::post('/non_ppn/datatablecogs',                              [NonppnController::class, 'datatabledetailcogs'])->name('create');
    Route::get('/non_ppn/deletedetail/{id}',                           [NonppnController::class, 'deletedetail'])->name('deletedetail');
    Route::post('/non_ppn/deletedetail/{id}',                          [NonppnController::class, 'deletedetail'])->name('deletedetail');
    Route::get('/non_ppn/createcogs',                                  [NonppnController::class, 'createCogs'])->name('createcogs');
    Route::post('/non_ppn/createcogs',                                 [NonppnController::class, 'createCogs'])->name('createcogs');
    Route::post('/non_ppn/updateTotalPpn',                             [NonppnController::class, 'updateTotalPpn'])->name('non_ppn.updateTotalPpn');
    Route::post('/non_ppn/updateincentive',                            [NonppnController::class, 'updateIncentive'])->name('non_ppn.updateincentive');
    Route::post('/non_ppn/updateValidasiPayment',                      [NonppnController::class, 'updateValidasiPayment'])->name('non_ppn/updateValidasiPayment');
    Route::post('/non_ppn/total_cogs',                                 [NonppnController::class, 'getTotalCogs']);
    Route::get('/non_ppn/getvendor',                                   [NonppnController::class, 'getvendor'])->name('non_ppn/getvendor');
    Route::post('/non_ppn/getvendor',                                  [NonppnController::class, 'getvendor'])->name('non_ppn/getvendor');

    Route::get('/non_ppn/detailUpdate', [NonppnController::class, 'detailUpdate'])->name('non_ppn/detailUpdate');
    Route::post('/non_ppn/detailUpdate', [NonppnController::class, 'detailUpdate'])->name('non_ppn/detailUpdate');
    Route::get('/non_ppn/detailUpdateCogs/{id}', [NonppnController::class, 'detailUpdateCogs'])->name('non_ppn/detailUpdateCogs');
    Route::post('/non_ppn/detailUpdateCogs/{id}', [NonppnController::class, 'detailUpdateCogs'])->name('non_ppn/detailUpdateCogs');
});

Route::get('/logout',                        [SesiController::class, 'logout']);


