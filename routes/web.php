<?php

use App\Http\Controllers\StaffController;
use App\Http\Controllers\booking\BookingController;
use App\Http\Controllers\KategoribarangController;
use App\Http\Controllers\laundry\LaundryController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\manager\ManagerController;
use App\Http\Controllers\manager\PaketLaundryController;
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
use App\Http\Controllers\qc\QcController;
use App\Http\Controllers\user\UserController;
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

Route::middleware(['UserAkses:staff'])->group(function() {


    Route::get('/staff',[StaffController::class, 'index']);

});

Route::middleware(['UserAkses:manager,super_admin'])->group(function() {

    //DASHBOARD MANAGER
    Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/super_admin',[ManagerController::class, 'index']);




//////BOOKING
    // getbarang
    Route::get('/booking/getKategori',                               [BookingController::class, 'getKategori'])->name('booking/getKategori');
    Route::post('/booking/getKategori',                              [BookingController::class, 'getKategori'])->name('booking/getKategori');
    Route::get('/pdf',                                               [PDFController::class, 'index'])->name('pdf/index');
    // getbarang
    Route::get('/booking/getBarang',                                 [BookingController::class, 'getBarang'])->name('booking/getBarang');
    Route::post('/booking/getBarang',                                [BookingController::class, 'getBarang'])->name('booking/getBarang');

    Route::get('/booking',                                           [BookingController::class, 'index'])->name('booking');
    Route::get('/booking/tambah_data_booking',                       [BookingController::class, 'tambah_data'])->name('tambah_data_booking');
    Route::get('/booking/datatable',                                 [BookingController::class, 'datatable'])->name('booking/datatable');
    Route::post('/booking/datatable',                                [BookingController::class, 'datatable'])->name('booking/datatable');

    Route::get('/booking/datatabledetail',                           [BookingController::class, 'datatabledetail'])->name('booking/datatabledetail');
    Route::post('/booking/datatabledetail',                          [BookingController::class, 'datatabledetail'])->name('booking/datatabledetail');

    Route::get('/booking/create',                                    [BookingController::class, 'create'])->name('create');
    Route::post('/booking/create',                                   [BookingController::class, 'create'])->name('create');
    Route::get('/booking/update/{id}',                               [BookingController::class, 'update'])->name('update');
    Route::post('/booking/update/{id}',                              [BookingController::class, 'update'])->name('update');
    Route::get('/booking/delete/{id}',                               [BookingController::class, 'delete'])->name('delete');
    Route::post('/booking/delete/{id}',                              [BookingController::class, 'delete'])->name('delete');

    Route::get('/booking/detailCreate',                              [BookingController::class, 'detailCreate'])->name('detailCreate');
    Route::post('/booking/detailCreate',                             [BookingController::class, 'detailCreate'])->name('detailCreate');
    Route::get('/booking/detailUpdate',                              [BookingController::class, 'detailUpdate'])->name('detailUpdate');
    Route::post('/booking/detailUpdate',                             [BookingController::class, 'detailUpdate'])->name('detailUpdate');
    Route::get('/booking/detailDelete',                              [BookingController::class, 'detailDelete'])->name('detailDelete');
    Route::post('/booking/detailDelete',                             [BookingController::class, 'detailDelete'])->name('detailDelete');

    Route::get('/booking/detail_data_booking',                       [BookingController::class, 'detail_data_booking']);


    //BARANG
    Route::get('/barang',                                            [BarangController::class, 'index'])->name('barang');
    Route::get('/barang/datatable',                                  [BarangController::class, 'datatable'])->name('barang/datatable');
    Route::post('/barang/datatable',                                 [BarangController::class, 'datatable'])->name('barang/datatable');
    Route::get('/barang/tambah_data_barang',                         [BarangController::class, 'tambah_data_barang']);

    Route::get('/barang/doSave',                                     [BarangController::class, 'doSave'])->name('barang/doSave');
    Route::post('/barang/doSave',                                    [BarangController::class, 'doSave'])->name('barang/doSave');
    Route::get('/barang/update/{id}',                                [BarangController::class, 'update'])->name('barang/update');
    Route::post('/barang/update/{id}',                               [BarangController::class, 'update'])->name('barang/update');

    Route::get('/barang/detail_barang',                              [BarangController::class, 'detail_barang']);

    // Route untuk form barang dengan stok
    Route::get('/barang/create-with-stok',                           [BarangController::class, 'createBarangWithStok'])->name('manager.barang.create-with-stok');
    Route::post('/barang/save-with-stok',                            [BarangController::class, 'saveBarangWithStok'])->name('manager.barang.save-with-stok');

    //KATEGORI
    Route::get('/kategori_barang',                                   [KategoribarangController::class, 'index'])->name('kategori_barang');
    Route::get('/kategori_barang/tambah_data_kategori',              [KategoribarangController::class, 'tambah_data_kategori']);
    Route::get('/kategori_barang/dosave',                            [KategoribarangController::class, 'dosave'])->name('kategori_barang/dosave');
    Route::post('/kategori_barang/dosave',                           [KategoribarangController::class, 'dosave'])->name('kategori_barang/dosave');


    //STOK BARANG
    Route::get('/stok_barang',                                      [StokbarangController::class, 'index'])->name('stok_barang');
    Route::get('/stok_barang/datatable',                            [StokbarangController::class, 'datatable'])->name('stok_barang/datatable');
    Route::post('/stok_barang/datatable',                           [StokbarangController::class, 'datatable'])->name('stok_barang/datatable');
    Route::get('/stok_barang/create',                               [StokbarangController::class, 'create'])->name('stok_barang/create');
    Route::post('/stok_barang/create',                              [StokbarangController::class, 'create'])->name('stok_barang/create');
    Route::get('/stok_barang/update',                               [StokbarangController::class, 'update'])->name('stok_barang/update');
    Route::post('/stok_barang/update',                              [StokbarangController::class, 'update'])->name('stok_barang/update');
    Route::get('/stok_barang/delete',                               [StokbarangController::class, 'delete'])->name('stok_barang/delete');
    Route::post('/stok_barang/delete',                              [StokbarangController::class, 'delete'])->name('stok_barang/delete');

    //REPORT
    Route::get('/report_booking',                                   [ReportBookingController::class, 'index'])->name('report_booking');
    Route::get('/report_booking/createPdfRekapBookingPerBulan',     [ReportBookingController::class, 'createPdfRekapBookingPerBulan'])->name('report_booking/createPdfRekapBookingPerBulan');
    Route::get('/report_laundry/createPdfRekapLaundryPerBulan',     [ReportLaundryController::class, 'createPdfRekapLaundryPerBulan'])->name('report_laundry/createPdfRekapLaundryPerBulan');
    Route::get('/report_sound/createPdfRekapSoundPerBulan',         [ReportSoundController::class, 'createPdfRekapSoundPerBulan'])->name('report_sound/createPdfRekapSoundPerBulan');




    Route::get('/report_absen',                                     [ReportAbsenController::class, 'index'])->name('report_absen');
    Route::get('/report_laundry',                                   [ReportLaundryController::class, 'index'])->name('report_laundry');
    Route::get('/report_soundsystem',                               [ReportSoundController::class, 'index'])->name('report_soundsystem');
    Route::get('/report_barang',                                    [ReportBarangController::class, 'index'])->name('report_barang');

   //MANAJEMEN AKUN
   Route::get('/manajemen_akun',                                    [ManajemenakunController::class, 'index'])->name('manajemen_akun');
   Route::get('/datatable',                                         [ManajemenakunController::class, 'datatable'])->name('manajemenakun/datatable');
   Route::post('/datatable',                                        [ManajemenakunController::class, 'datatable'])->name('manajemenakun/datatable');
   Route::get('/manajemen_akun/create',                             [ManajemenakunController::class, 'create'])->name('create');
   Route::post('/manajemen_akun/create',                            [ManajemenakunController::class, 'create'])->name('create');
   Route::get('/manajemen_akun/delete/{id}',                        [ManajemenakunController::class, 'delete'])->name('delete');
   Route::post('/manajemen_akun/delete/{id}',                       [ManajemenakunController::class, 'delete'])->name('delete');


    // Route::get('/absen',[AbsenController::class, 'index']);
    // Route::get('/logout', [SesiController::class, 'logout']);
});

//STAFF
Route::middleware(['UserAkses:staff,super_admin'])->group(function() {
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

//PRWAPU
Route::middleware(['UserAkses:staff,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/pr_wapu/getSales',                                    [WapuController::class, 'getSales'])->name('pr_wapu/getSales');
    Route::post('/pr_wapu/getSales',                                   [WapuController::class, 'getSales'])->name('pr_wapu/getSales');
    Route::get('/pr_wapu/getvendor',                                   [WapuController::class, 'getvendor'])->name('pr_wapu/getvendor');
    Route::post('/pr_wapu/getvendor',                                  [WapuController::class, 'getvendor'])->name('pr_wapu/getvendor');
    Route::get('/pr_wapu',                                             [WapuController::class, 'index']);
    Route::get('/pr_wapu',                                             [WapuController::class, 'index']);
    Route::get('/pr_wapu/datatable',                                   [WapuController::class, 'datatable'])->name('pr_wapu/datatable');
    Route::post('/pr_wapu/datatable',                                  [WapuController::class, 'datatable'])->name('create');
    Route::get('/pr_wapu/create',                                      [WapuController::class, 'create'])->name('create');
    Route::post('/pr_wapu/create',                                     [WapuController::class, 'create'])->name('create');
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
    Route::post('/pr_wapu/updatesharingprovit',                        [WapuController::class, 'updateSharingProvit'])->name('pr_wapu.updatesharingprovit');
    Route::post('/pr_wapu/updateincentive',                            [WapuController::class, 'updateIncentive'])->name('pr_wapu.updateincentive');
    Route::post('/pr_wapu/updateValidasiPayment',                      [WapuController::class, 'updateValidasiPayment'])->name('pr_wapu/updateValidasiPayment');
    Route::post('/pr_wapu/total_cogs',                                 [WapuController::class, 'getTotalCogs']);

    Route::get('/pr_wapu/detailUpdate/{id}',                           [WapuController::class, 'detailUpdate'])->name('pr_wapu.detailUpdate');
    // Route::post('/pr_wapu/detailUpdate/{id}',                       [WapuController::class, 'detailUpdate'])->name('pr_wapu.detailUpdate');

    Route::get('/pr_wapu/detailUpdateCogs/{id}',                       [WapuController::class, 'detailUpdateCogs'])->name('pr_wapu/detailUpdateCogs');
    Route::post('/pr_wapu/detailUpdateCogs/{id}',                      [WapuController::class, 'detailUpdateCogs'])->name('pr_wapu/detailUpdateCogs');

    Route::post('/pr_wapu/detailUpdate',                               [WapuController::class, 'detailUpdate'])->name('pr_wapu.detailUpdate');

});

////SWASTA
Route::middleware(['UserAkses:staff,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/swasta',                                              [WapuController::class, 'index']);
    Route::get('/swasta/getSales',                                     [SwastaController::class, 'getSales'])->name('swasta/getSales');
    Route::post('/swasta/getSales',                                    [SwastaController::class, 'getSales'])->name('swasta/getSales');
    Route::get('/swasta/datatable',                                    [SwastaController::class, 'datatable'])->name('swasta/datatable');
    Route::post('/swasta/datatable',                                   [SwastaController::class, 'datatable'])->name('create');
    Route::get('/swasta/create',                                       [SwastaController::class, 'create'])->name('create');
    Route::post('/swasta/create',                                      [SwastaController::class, 'create'])->name('create');
    Route::get('/swasta/update/{id}',                                  [SwastaController::class, 'update'])->name('update');
    Route::post('/swasta/update/{id}',                                 [SwastaController::class, 'update'])->name('update');
    Route::get('/swasta/delete/{id}',                                  [SwastaController::class, 'delete'])->name('delete');
    Route::post('/swasta/delete/{id}',                                 [SwastaController::class, 'delete'])->name('delete');
    Route::get('/swasta/generate-nomor-pr',                            [SwastaController::class, 'generateNomorPR'])->name('swasta.generate_nomor_pr');
    // Route::get('/swasta/detail_data_swasta',                           [SwastaController::class, 'detail_data_swasta']);
    Route::get('/swasta/datatabledetail',                              [SwastaController::class, 'datatabledetail'])->name('swasta/datatabledetail');
    Route::post('/swasta/datatabledetail',                             [SwastaController::class, 'datatabledetail'])->name('swasta/datatabledetail');
    Route::get('/swasta/detailCreate',                                 [SwastaController::class, 'detailCreate'])->name('detailCreate');
    Route::post('/swasta/detailCreate',                                [SwastaController::class, 'detailCreate'])->name('detailCreate');
    Route::get('/swasta/datatablecogs',                                [SwastaController::class, 'datatabledetailcogs'])->name('swasta/datatablecogs');
    Route::post('/swasta/datatablecogs',                               [SwastaController::class, 'datatabledetailcogs'])->name('create');
    Route::get('/swasta/deletedetail/{id}',                            [SwastaController::class, 'deletedetail'])->name('deletedetail');
    Route::post('/swasta/deletedetail/{id}',                           [SwastaController::class, 'deletedetail'])->name('deletedetail');
    Route::get('/swasta/createcogs',                                   [SwastaController::class, 'createCogs'])->name('createcogs');
    Route::post('/swasta/createcogs',                                  [SwastaController::class, 'createCogs'])->name('createcogs');
    Route::post('/swasta/updateTotalPpn',                              [SwastaController::class, 'updateTotalPpn'])->name('swasta.updateTotalPpn');
    Route::post('/swasta/updateincentive',                             [SwastaController::class, 'updateIncentive'])->name('swasta.updateincentive');
    Route::post('/swasta/updateValidasiPayment',                       [SwastaController::class, 'updateValidasiPayment'])->name('swasta/updateValidasiPayment');
    Route::post('/swasta/total_cogs',                                  [SwastaController::class, 'getTotalCogs']);

    Route::get('/swasta/detailUpdateCogs/{id}',                        [SwastaController::class, 'detailUpdateCogs'])->name('swasta/detailUpdateCogs');
    Route::post('/swasta/detailUpdateCogs/{id}',                       [SwastaController::class, 'detailUpdateCogs'])->name('swasta/detailUpdateCogs');
});


Route::middleware(['UserAkses:staff,super_admin'])->group(function() {
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


    // Route::get('/swasta/update/{id}',                                  [SwastaController::class, 'update'])->name('update');
    // Route::post('/swasta/update/{id}',                                 [SwastaController::class, 'update'])->name('update');
    // Route::get('/swasta/delete/{id}',                                  [SwastaController::class, 'delete'])->name('delete');
    // Route::post('/swasta/delete/{id}',                                 [SwastaController::class, 'delete'])->name('delete');
    // Route::get('/swasta/detail_data_swasta',                           [SwastaController::class, 'detail_data_swasta']);
    // Route::get('/swasta/datatabledetail',                              [SwastaController::class, 'datatabledetail'])->name('swasta/datatabledetail');
    // Route::post('/swasta/datatabledetail',                             [SwastaController::class, 'datatabledetail'])->name('swasta/datatabledetail');
    // Route::get('/swasta/detailCreate',                                 [SwastaController::class, 'detailCreate'])->name('detailCreate');
    // Route::post('/swasta/detailCreate',                                [SwastaController::class, 'detailCreate'])->name('detailCreate');
    // Route::get('/swasta/datatablecogs',                                [SwastaController::class, 'datatabledetailcogs'])->name('swasta/datatablecogs');
    // Route::post('/swasta/datatablecogs',                               [SwastaController::class, 'datatabledetailcogs'])->name('create');
    // Route::get('/swasta/deletedetail/{id}',                            [SwastaController::class, 'deletedetail'])->name('deletedetail');
    // Route::post('/swasta/deletedetail/{id}',                           [SwastaController::class, 'deletedetail'])->name('deletedetail');
    // Route::get('/swasta/createcogs',                                   [SwastaController::class, 'createCogs'])->name('createcogs');
    // Route::post('/swasta/createcogs',                                  [SwastaController::class, 'createCogs'])->name('createcogs');
    // Route::post('/swasta/updateTotalPpn',                              [SwastaController::class, 'updateTotalPpn'])->name('swasta.updateTotalPpn');
    // Route::post('/swasta/updateincentive',                             [SwastaController::class, 'updateIncentive'])->name('swasta.updateincentive');
    // Route::post('/swasta/updateValidasiPayment',                       [SwastaController::class, 'updateValidasiPayment'])->name('swasta/updateValidasiPayment');
    // Route::post('/swasta/total_cogs',                                  [SwastaController::class, 'getTotalCogs']);

    // Route::get('/swasta/detailUpdateCogs/{id}',                        [SwastaController::class, 'detailUpdateCogs'])->name('swasta/detailUpdateCogs');
    // Route::post('/swasta/detailUpdateCogs/{id}',                       [SwastaController::class, 'detailUpdateCogs'])->name('swasta/detailUpdateCogs');
});

//DELIVERY ORDER
    Route::middleware(['UserAkses:staff,super_admin'])->group(function() {
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
Route::middleware(['UserAkses:staff,super_admin'])->group(function() {
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

//QUALITY CONTROL
Route::middleware(['UserAkses:staff,super_admin'])->group(function() {
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
Route::middleware(['UserAkses:staff,super_admin'])->group(function() {
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

/////SOUND SYSTEM
Route::middleware(['UserAkses:sound_system,super_admin'])->group(function() {
    // Route::get('/manager',[ManagerController::class, 'index']);
    Route::get('/sound',                                             [SoundController::class, 'index']);
    Route::get('/sound/getdata_sound',                               [SoundController::class, 'getdata_sound'])->name('getdata_sound');
    Route::get('/sound/tambah_data_sound',                           [SoundController::class, 'tambah_data_sound']);
    Route::post('/sound/proses_tambah_sound',                        [SoundController::class, 'proses_tambah_sound']);
    Route::get('/sound/datatable',                                   [SoundController::class, 'datatable'])->name('sound/datatable');
    Route::get('/sound/datatable',                                   [SoundController::class, 'datatable'])->name('sound/datatable');
    Route::post('/sound/datatable',                                  [SoundController::class, 'datatable'])->name('create');
    Route::get('/sound/create',                                      [SoundController::class, 'create'])->name('create');
    Route::post('/sound/create',                                     [SoundController::class, 'create'])->name('create');
    Route::get('/sound/update/{id}',                                 [SoundController::class, 'update'])->name('update');
    Route::post('/sound/update/{id}',                                [SoundController::class, 'update'])->name('update');
    Route::get('/sound/delete/{id}',                                      [SoundController::class, 'delete'])->name('delete');
    Route::post('/sound/delete/{id}',                                     [SoundController::class, 'delete'])->name('delete');
    Route::get('/sound/getpaketsound', [SoundController::class, 'getpaketsound'])->name('sound/getpaketsound');
    Route::post('/sound/getpaketsound', [SoundController::class, 'getpaketsound'])->name('sound/getpaketsound');
});

Route::get('/logout',                        [SesiController::class, 'logout']);


