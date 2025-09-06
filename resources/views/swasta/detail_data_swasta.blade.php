@extends('layouts.manager.template_manager')

@push('css')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<style>
    :root {
    --main-bg: #f8fafc;
    --card-bg: #fff;
    --primary: #5897fb;
    --secondary: #b3b3b4;
    --soft-shadow: 0 2px 12px rgba(0,0,0,0.07);
    --border-radius: 1rem;
  }
  body, main {
    background: var(--main-bg) !important;
  }
  .card {
    border-radius: var(--border-radius);
    box-shadow: var(--soft-shadow);
    border: none;
    margin-bottom: 1.5rem;
    background: var(--card-bg);
  }
  .card-header {
    background: linear-gradient(90deg, #e0e7ff 0%, #f8fafc 100%);
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    border-bottom: none;
    padding: 1rem 1.5rem;
  }
  .btn {
    border-radius: 0.5rem;
    transition: background 0.2s, color 0.2s;
    font-weight: 500;
  }
  .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  .table-responsive {
    overflow-x: auto;
  }
  .table th, .table td {
    vertical-align: middle !important;
    font-size: 0.98rem;
  }
  .badge-status {
    font-size: 0.9em;
    padding: 0.4em 0.7em;
    border-radius: 0.5em;
  }
  .badge-approve {
    background: #d1fae5;
    color: #065f46;
  }
  .badge-need {
    background: #fef3c7;
    color: #92400e;
  }
  @media (max-width: 991.98px) {
    .card-header > div {
      flex-wrap: wrap !important;
      gap: 0.5rem;
    }
    .btn, .input-group-text, .form-control {
      font-size: 0.95rem;
      padding: 0.4rem 0.7rem;
    }
    .modal-dialog {
      max-width: 95vw;
      margin: 1.75rem auto;
    }
  }
  @media (max-width: 767.98px) {
    .table thead {
      font-size: 12px;
    }
    .table td, .table th {
      font-size: 12px;
      padding: 0.3rem;
    }
    .card .card-header h6 {
      font-size: 1rem;
    }
    .modal-dialog {
      max-width: 98vw;
      margin: 0.5rem auto;
    }
  }
  .table-responsive::-webkit-scrollbar {
    height: 6px;
    background: #f1f1f1;
  }
  .table-responsive::-webkit-scrollbar-thumb {
    background: var(--secondary);
    border-radius: 3px;
  }
</style>
@endpush

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">

        <!-- BARANG/PRODUK -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Data PR Swasta - Detail</h6>
              <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div class="ml-auto">
                    <button type="button" class="btn btn-info" id="btn-back">
                        <i class="fa-solid fa-arrow-left" style="margin-right: 10px;"></i> Kembali
                      </button>
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#formModal">
                        <i class="fa-solid fa-plus fa-lg" style="margin-right: 10px"></i>Tambah Data
                      </button>
                      <button type="button" class="btn btn-warning" id="btn-edit" data-toggle="modal" data-target="#formModal">
                        <i class="fa-solid fa-pencil" style="margin-right: 10px;"></i> Ubah Data
                      </button>
                      <button type="button" class="btn btn-danger" id="btn-delete">
                        <i class="fa-solid fa-trash" style="margin-right: 10px;"></i> Hapus Data
                      </button>

                  </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table id="datatable" class="table table-striped table-bordered basic-datatables">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis PPN</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Partnumber</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Partnumber/Description</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vendor</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">QTY</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unit Price</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Price</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vendor Price / Unit</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unit Price CV</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total PO CV</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Cost</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Margin</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Persentase</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>


                  <div class="card-header pb-0">
                    <h6>Data Additional Cost</h6>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="ml-auto">
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formCogs">
                           <i class="fa-solid fa-plus fa-lg" style="margin-right: 10px"></i>Add Cogs
                          </button>
                           <button type="button" class="btn btn-warning" id="btn-edit-cogs" data-toggle="modal" data-target="#formCogs">
                         <i class="fa-solid fa-pencil" style="margin-right: 10px;"></i> Ubah Data
                          </button>
                   </div>
                  </div>
                  </div>
                  <div class="card-body px-0 pt-0 pb-2">
                      <div class="table-responsive p-0">
                          <table id="datatable-cogs" class="table table-striped table-bordered">
                              <thead>
                                <tr>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Expedittion</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Add Insentif</th>
                                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Instalasi Setting</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">PPH / BANK FEE</th>
                                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Other</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                            </table>
                      </div>

                      <div class="row mt-3" style="margin-right: 100px;">
                          <div class="col-12">
                            <div class="card">
                              <div class="card-body">

                                <div class="row">
                                  <div class="col-md-4">
                                      <table class="table table-borderless">
                                        <tr>
                                          <td class="text-right font-weight-bold">Subtotal COGS</td>
                                          <td class="text-right font-weight-bold" id="subtotal_cogs">Rp 0</td>
                                        </tr>
                                      </table>
                                    </div>
                              </div>
                              </div>
                            </div>
                          </div>
                        </div>
                  </div>


                  <!-- Subtotal ROW PPN -->
                  <div class="row mt-3" style="margin-right: 100px;">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                            <form id="form-update-ppn" method="POST" action="{{ url('/pr_wapu/updateTotalPpn') }}">
                                @csrf
                                <input type="hidden" name="id_projek" value="{{ $id_projek ?? '' }}">
                                <div class="row mt-3" style="margin-right: 100px;">
                                    <div class="col-12">
                                      <div class="card">
                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Subtotal</span>
                                                    </div>
                                                    <input type="text" class="form-control font-weight-bold text-right" id="subtotal-price" name="subtotal_price" value="@if(isset($subtotal[0]))Rp {{ number_format($subtotal[0]->subtotal_price ?? 0, 0, ',', '.') }}@else Rp 0 @endif" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Subtotal Cost</span>
                                                    </div>
                                                    <input type="text" class="form-control font-weight-bold text-right" id="subtotal_cost" name="subtotal_cost" value="Rp 0" readonly>
                                                </div>
                                              </div>

                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Subtotal SP2D</span>
                                                        </div>
                                                        <input type="text" class="form-control font-weight-bold text-right" id="subtotal_sp2d" name="subtotal_sp2d" value="Rp 0" readonly>
                                                    </div>
                                                </div>

                                        </div>

                                            <div class="row">

                                              <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">PPN 11%</span>
                                                    </div>
                                                    <input type="text" class="form-control font-weight-bold text-right" id="jumlah-ppn" name="jumlah_ppn" value="Rp 0" readonly>
                                                </div>
                                              </div>

                                              <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span hidden class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Persentase Margin</span>
                                                    </div>
                                                    <input hidden type="text" class="form-control font-weight-bold text-right" id="" name="" value="0%" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Margin</span>
                                                    </div>
                                                    <input type="text" class="form-control font-weight-bold text-right" id="total_margin" name="total_margin" value="0%" readonly>
                                                </div>
                                            </div>

                                            </div>

                                                <div class="row">

                                                     <div class="col-md-4">
                                                         <div class="input-group mb-3">
                                                             <div class="input-group-prepend">
                                                                 <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Inc Vat</span>
                                                             </div>
                                                             <input type="text" class="form-control font-weight-bold text-right" id="total-vat" name="total_vat" value="Rp 0" readonly>
                                                         </div>
                                                     </div>

                                                </div>

                                        </div>
                                      </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Simpan Perubahan</button>
                                      </div>
                                  </div>
                            </form>
                      </div>
                    </div>
                  </div>
                </div>

                  <!-- End Subtotal Row -->
              </div>
            </div>
          </div>

          <div class="row gx-3 gy-3">
          <!-- VALIDASI PAYMENT -->
          <div class="card mb-4 col-md-12">
            <div class="card-header pb-0">
              <h6>Validasi Payment</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">

                </div>

                <div class="row mt-3" style="margin-right: 100px;">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">

                            <!-- Form pertama - Validasi Payment -->
                            <form id="form_update_validasi_payment" method="POST" action="{{ url('/pr_wapu/updateValidasiPayment') }}">
                                @csrf
                                <input type="hidden" name="id_projek" value="{{ $id_projek ?? '' }}">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Validasi Payment</span>
                                            </div>
                                            <input type="text" class="form-control font-weight-bold text-right" id="validasi_payment" name="validasi_payment" value="{{ isset($validasi_payment) && $validasi_payment !== '' ? 'Rp ' . number_format($validasi_payment, 0, ',', '.') : '' }}" @if(Auth::user()->role !== 'super_admin') readonly @endif>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">PPH / Bank FEE</span>
                                            </div>
                                            <input type="text" class="form-control font-weight-bold text-right" id="pph_bank_fee" name="pph_bank_fee" value="{{ isset($pph_bank_fee) && $pph_bank_fee !== '' && $pph_bank_fee !== 0 ? 'Rp ' . number_format($pph_bank_fee, 0, ',', '.') : '-' }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Simpan Perubahan</button>
                                </div>
                            </form>

                        </div>
                      </div>
                    </div>
                  </div>


            </div>
          </div>

        </div>
        <div class="card mb-4 col-md-12">
          <div class="card-header pb-0">
            <h6>Date Of Approval</h6>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">

              </div>

              <div class="row mt-3" style="margin-right: ;">
                  <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Form kedua - Date Of Approval -->
                            <form id="form_update_incentive" method="POST" action="{{ url('/pr_wapu/updateincentive') }}">
                                @csrf
                                <input type="hidden" name="id_projek" value="{{ $id_projek ?? '' }}">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Incentive Sales</span>
                                            </div>
                                            <input type="text" name="incentive_sales" id="incentive_sales" class="form-control pl-2" style="border: 1px solid black;" value="{{ isset($incentive_sales) && $incentive_sales !== '' ? 'Rp ' . number_format($incentive_sales, 0, ',', '.') : 'Rp 0' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Persentase Incentive</span>
                                            </div>
                                            <input type="text" name="persentase_incentive" id="persentase_incentive" class="form-control pl-2" style="border: 1px solid black;" value="{{ isset($incentive_sales) && $incentive_sales !== '' && $incentive_sales !== 0 ? 'Rp ' . number_format($incentive_sales, 0, ',', '.') : 'Rp 0' }}" readonly>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Incentive fe001a</span>
                                            </div>
                                            <input type="text" name="incentive_fe001a" id="incentive_fe001a" class="form-control pl-2" style="border: 1px solid black;" value="{{ isset($incentive_fe001a) && $incentive_fe001a !== '' && $incentive_fe001a !== 0 ? 'Rp ' . number_format($incentive_fe001a, 0, ',', '.') : 'Rp 0' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Persentase fe001a</span>
                                            </div>
                                            <input type="text" name="persentase_fe001a" id="persentase_fe001a" class="form-control pl-2" style="border: 1px solid black;" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Approval By</span>
                                            </div>
                                            <input type="text" name="approval" id="approval" class="form-control pl-2" style="border: 1px solid black;" value="{{ isset($approval) && $approval !== '' && $approval !== 0 ? 'Rp ' . number_format($approval, 0, ',', '.') : 'Agus Sopyan' }}" readonly>
                                        </div>
                                    </div>

                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <label class="input-group-text" for="inputGroupSelect01">Status</label>
                                        </div>
                                        <select class="custom-select" name="status" id="jenis_approve">
                                          <option selected>Choose...</option>
                                          <option value="approve" style="background-color: greenyellow;">Approve</option>
                                          <option value="need_approve" style="background-color: red;">Need Approve</option>
                                        </select>
                                      </div>
                                  </div>

                                </div>

                              <div class="text-center">
                                  <button type="submit" class="btn btn-primary btn-sm mt-2">Simpan Perubahan</button>
                              </div>
                          </form>

                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </div>

        <div class="card mb-4 col-md-12">
            <div class="card-header pb-0">
              <h6>Total PO Ke CV MBS</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">

                </div>

                <div class="row mt-3" style="margin-right: ;">
                    <div class="col-12">
                      <div class="card">
                          <div class="card-body">

                              <!-- Form kedua - Date Of Approval -->
                              <form id="form_update_po" method="POST" action="{{ url('/pr_wapu/updateTotalPO') }}">
                                  @csrf
                                  <input type="hidden" name="id_projek" value="{{ $id_projek ?? '' }}">
                                  <div class="row">
                                    <div class="col-md-4">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total PO PPN</span>
                                            </div>
                                            <input type="text" class="form-control font-weight-bold text-right" id="subtotal-ppn" name="total_po_ppn" readonly value="Rp 0">
                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Cost PPN</span>
                                            </div>
                                            <input type="text" class="form-control font-weight-bold text-right" id="subtotal-cost-ppn" name="total_cost_ppn" readonly value="Rp 0">
                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Margin PPN</span>
                                            </div>
                                            <input type="text" class="form-control font-weight-bold text-right" id="total-margin-ppn" name="total_margin_ppn" readonly value="Rp 0">
                                        </div>

                                    </div>
                                </div>


                          <div class="row mt-4">

                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total PO Non PPN</span>
                                    </div>
                                    <input type="text" class="form-control font-weight-bold text-right" id="subtotal-non-ppn" name="total_po_non_ppn" value="Rp 0" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Cost Non PPN</span>
                                    </div>
                                    <input type="text" class="form-control font-weight-bold text-right" id="subtotal-cost-non-ppn" name="total_cost_non_ppn" value="Rp 0" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Margin Non PPN</span>
                                    </div>
                                    <input type="text" class="form-control font-weight-bold text-right" id="subtotal-margin-non-ppn" name="total_margin_non_ppn" value="Rp 0" readonly>
                                </div>
                            </div>

                        </div>


                        <div class="row mt-4">

                                <div class="col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Sub Total PPN/Non PPN</span>
                                        </div>
                                        <input type="text" class="form-control font-weight-bold text-right" id="subtotal-po-cv" name="subtotal_po_cv" value="Rp 0" readonly style="width: 150px">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Cost</span>
                                        </div>
                                        <input type="text" class="form-control font-weight-bold text-right" id="subtotal-po-cost-cv" name="subtotal_po_cost_cv" value="Rp 0" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Margin</span>
                                        </div>
                                        <input type="text" class="form-control font-weight-bold text-right" id="subtotal-margin-cv" name="subtotal_margin_cv" value="Rp 0" readonly>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-4 mt-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Total Persentase</span>
                                    </div>
                                    <input type="text" class="form-control font-weight-bold text-right" id="subtotal-persentase-cv" name="subtotal_persentase_cv" value="Rp 0" readonly>
                                </div>
                            </div>

                                  </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Simpan Perubahan</button>
                                </div>
                            </form>

                        </div>
                      </div>
                    </div>
                  </div>
            </div>
          </div>

        </div>
      </div>
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">MBS</a>
                for a better web.
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>



  {{-- form modal --}}
  <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form - Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_booking">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01">Jenis PPN</label>
                                </div>
                                <select class="custom-select" name="jenis_ppn" id="inputGroupSelect01">
                                  <option selected>Choose...</option>
                                  <option value="ppn">PPN</option>
                                  <option value="non_ppn">NON PPN</option>
                                </select>
                              </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Partnumber</span>
                                </div>
                                <input type="text" name="part_number" id="part_number" class="form-control pl-2" style="border: 1px solid black;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="background-color: rgb(222, 222, 222);">Partnumber/Description</span>
                                </div>
                                <textarea class="form-control" aria-label="With textarea" name="partnumber_description" id="partnumber_description" style="border: 1px solid black;"></textarea>
                              </div>
                        </div>
                    </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="qty" class="form-label">QTY</label>
                                <input type="number" class="form-control" id="qty" name="qty" placeholder="Masukkan jumlah">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="Unit_price" class="form-label">Selling Price/Unit</label>
                                <input type="text" class="form-control" id="Unit_price" name="unit_price" placeholder="Harga per unit">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group-prepend">
                                    <label for="Unit_price" class="form-label">Vendor</label>
                                  </div>
                                  <select name="cmb_vendor" id="cmb_vendor" class="bg-danger"></select>
                            </div>
                        </div>


                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Total Price</span>
                        </div>
                        <input type="text" name="total_price" id="total_price" class="form-control pl-2" style="border: 1px solid black;" readonly>
                    </div>

                    <div class="input-group mb-3 mt-4">
                        <div class="input-group-prepend">
                            <h5 class="modal-title" id="exampleModalLabel">PO Ke CV MBS</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Vendor Price/Unit</span>
                                </div>
                                <input type="text" name="vendor_price" id="vendor_price" class="form-control pl-2" style="border: 1px solid black;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Unit Price</span>
                                </div>
                                <input type="text" name="unit_price_cv" id="unit_price_cv" class="form-control pl-2" style="border: 1px solid black;" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Total PO</span>
                                </div>
                                <input type="text" name="total_po_cv" id="total_po_cv" class="form-control pl-2" style="border: 1px solid black;" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Total Cost</span>
                                </div>
                                <input type="text" name="total_cost" id="total_cost" class="form-control pl-2" style="border: 1px solid black;" readonly>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Margin</span>
                                </div>
                                <input type="text" name="margin" id="margin" class="form-control pl-2" style="border: 1px solid black;" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Persentase</span>
                                </div>
                                <input type="text" name="persentase" id="persentase" class="form-control pl-2" style="border: 1px solid black;" readonly>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button id="btn_submit" type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  {{-- form modalCOGS --}}
  <div class="modal fade" id="formCogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form - COGS</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_cogs">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Expedittion</span>
                                </div>
                                <input type="text" name="expedittion" id="expedittion" class="form-control pl-2" style="border: 1px solid black;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="width: 160px; height: 35px; background-color: rgb(222, 222, 222);">Add Incentive fe001a</span>
                                </div>
                                <input type="text" name="add_insentif_fe001a" id="add_insentif_fe001a" class="form-control pl-2" style="border: 1px solid black;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Instalasi Setting</span>
                        </div>
                        <input type="text" name="instalasi_setting" id="instalasi_setting" class="form-control pl-2" style="border: 1px solid black;">
                    </div>
                </div>

                    <div class="row">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="height: 35px; background-color: rgb(222, 222, 222);">Other</span>
                        </div>
                        <input type="text" name="other" id="other" class="form-control pl-2" style="border: 1px solid black;">
                    </div>
                </div>

                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button id="btn_submit" type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>


  @push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
  <script>
    window.defaultUrl = `{{ url('/pr_wapu/') }}/`;

    let modal = $("#formModal");
    let modalCogs = $("#formCogs");
    let baseCostCogs = 0;
    let tableDetail; // untuk tabel utama
    let tableCogs;   // untuk tabel cogs

    $(document).ready(function() {
        viewDatatable();
        viewDatatableCogs();
        koleksiSelect2();

        // Hapus titik pada semua field form COGS sebelum submit
        $('#form_cogs').on('submit', function() {
            $(this).find('input, textarea').each(function() {
                if (this.type === 'hidden' || this.disabled) return;
                const $el = $(this);
                const v = $el.val();
                if (typeof v === 'string') {
                    $el.val(v.replace(/\./g, ''));
                }
            });
        });

        $('#btn-edit-cogs').prop('disabled', true);

    $('select[name=cmb_vendor').on('select2:select', function (e) {
        var data = e.params.data;
        // alert(data)
        // $('#total_harga').val();
    });

    // Event handler untuk perubahan data pada tabel COGS
    tableCogs.on('draw', function() {
        updateAllCalculations();
        updateCogsCalculations();
    });

    // Event handler untuk input manual yang mempengaruhi perhitungan
    $('#qty, #Unit_price, #vendor_price').on('input', function() {
        updateFormCalculations();
    });

    // Event handler untuk perubahan jenis PPN
    $('#inputGroupSelect01').on('change', function() {
        updateFormCalculations();
    });

    // Event handler untuk validasi payment
    $('#validasi_payment').on('input', function() {
        updatePaymentCalculations();
    });

    // Event handler untuk input COGS
    $('#expedittion, #add_insentif_fe001a, #instalasi_setting, #other').on('input', function() {
        updateCogsCalculations();
    });

    // Tambahkan event handler untuk semua input COGS
$('#add_insentif_fe001a, #instalasi_setting, #pph_bank_fee, #other').on('input', function() {
    let expedittionValue = parseFloat($('#expedittion').val().replace(/[^,\d]/g, '').replace(',', '.')) || 0;
    let addInsentifValue = parseFloat($('#add_insentif_fe001a').val()) || 0;
    let instalasiValue = parseFloat($('#instalasi_setting').val().replace(/[^,\d]/g, '').replace(',', '.')) || 0;
    let pphValue = parseFloat($('#pph_bank_fee').val().replace(/[^,\d]/g, '').replace(',', '.')) || 0;
    let otherValue = parseFloat($('#other').val().replace(/[^,\d]/g, '').replace(',', '.')) || 0;

    let total = baseCostCogs + expedittionValue + addInsentifValue + instalasiValue + pphValue + otherValue;
    $('#total_cost_cogs').text('Rp ' + formatRupiahWithDots(total.toString(), ''));
});

    $("#btn-back").on("click", function () {
        window.location.href = defaultUrl;
    });

    $("#btn-edit").on("click", function () {
        let selected = tableDetail.row('.selected').data();

        if (_.isEmpty(selected) ||  selected == undefined) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Pilih Data Terlebih Dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        $("input[name=_type]").val("update");
        $("input[name=id]").val(selected.id);
        $("select[name=jenis_ppn]").val(selected.jenis_ppn);


        $("select[name=cmb_vendor]").select2("trigger", "select", {
            data: {
                id: selected.id_vendor,
                text: selected.nama_vendor
            }
        });


        $('#jenis_ppn').val(selected.jenis_ppn);
        $('#part_number').val(selected.part_number);
        $('#partnumber_description').val(selected.partnumber_description);
        $('#Unit_price').val(selected.unit_price);
        $('#total_price').val(selected.total_price);
        $('#qty').val(selected.qty);
        $('#vendor_price').val(selected.vendor_price);
        $('#unit_price_cv').val(selected.unit_price_cv);
        $('#total_po_cv').val(selected.total_po_cv);
        $('#total_cost').val(selected.total_cost);
        $('#margin').val(selected.margin);
        $('#persentase').val(selected.persentase);

        resetErrors();
        modal.modal("show");
    });


    $("#btn-edit-cogs").on("click", function () {
        let selected = tableCogs.row('.selected').data();

        if (_.isEmpty(selected) ||  selected == undefined) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Pilih Data Terlebih Dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        $("input[name=_type]").val("update");
        $("input[name=id]").val(selected.id);

        $('#expedittion').val(selected.expedittion);
        $('#add_insentif_fe001a').val(selected.add_insentif_fe001a);
        $('#instalasi_setting').val(selected.instalasi_setting);
        $('#other').val(selected.other);

        resetErrors();
        modal.modal("show");
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    modal.find("form").on("submit", function(ev) {
        ev.preventDefault();

        let submitButton = $(this).find("[type=submit]");
        let originalContent = submitButton.html();
        submitButton.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...');
        submitButton.prop("disabled", true);

        let type = $("[name=_type]").val();
        let id = $("[name=id]").val();
        let url = type == "create" ? defaultUrl + "detailCreate" : defaultUrl + "detailUpdate";

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize() + "&id_projek=" + "<?php echo $id_projek ?>",
            dataType: 'json',
            success: function(response) {
                // Tampilkan SweetAlert
                viewDatatable();
                Swal.fire({
                    title: 'Sukses',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reset seluruh form
                        $('#form_booking')[0].reset();
                        // Reset select2
                        $('#cmb_kategori').val(null).trigger('change.select2');
                        $('#cmb_barang').val(null).trigger('change.select2');
                        // Kosongkan input readonly
                        $('#total_price').val('');
                        $('#unit_price_cv').val('');
                        $('#total_po_cv').val('');
                        $('#total_cost').val('');
                        $('#margin').val('');
                        $('#persentase').val('');
                        // Kembalikan _type ke create
                        $("input[name=_type]").val("create");
                        // Reload datatable
                        tableDetail.ajax.reload();
                        // HAPUS KODE PENUTUPAN MODAL - FORM TIDAK AKAN TERTUTUP OTOMATIS
                        // $('#formModal').modal('hide');
                        // setTimeout(function() {
                        //     $('.modal-backdrop').remove();
                        //     $('body').removeClass('modal-open');
                        //     $('body').css('padding-right', '');
                        //     $('#formModal').hide();
                        // }, 150);
                    }
                });
            },
            error: function(jqXHR) {
                if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    let errors = jqXHR.responseJSON.errors;
                    for (let field in errors) {
                        let el = $(`[name="${field}"]`);
                        el.addClass("is-invalid");
                        el.next('.invalid-feedback').text(errors[field]);
                    }
                }
                alert('Terjadi kesalahan saat menyimpan data');
            },
            complete: function() {
                submitButton.html(originalContent);
                submitButton.prop("disabled", false);
            }
        });
    });

    modalCogs.find("form").on("submit", function(ev) {
        ev.preventDefault();
        let submitButton = $(this).find("[type=submit]");
        let originalContent = submitButton.html();
        submitButton.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...');
        submitButton.prop("disabled", true);

        let type = $("[name=_type]").val();
        let id = $("[name=id]").val();
        let url = type == "create" ? defaultUrl + "createcogs" : defaultUrl + "detailUpdateCogs/" + id;

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize() + "&id_projek=" + "<?php echo $id_projek ?>",
            dataType: 'json',
            success: function(response) {
                viewDatatable();
                Swal.fire({
                    title: 'Sukses',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reset seluruh form
                        $('#form_cogs')[0].reset();
                        // ... kode reset lain ...
                        tableCogs.ajax.reload();
                        $('#total_cost_cogs').text('Rp 0');
                        baseCostCogs = 0;

                        $('#formCogs').modal('hide');
                        setTimeout(function() {
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                            $('#formCogs').hide();
                        }, 150);
                    }
                });
            },
            error: function(jqXHR) {
                if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    let errors = jqXHR.responseJSON.errors;
                    for (let field in errors) {
                        let el = $(`[name="${field}"]`);
                        el.addClass("is-invalid");
                        el.next('.invalid-feedback').text(errors[field]);
                    }
                }
                alert('Terjadi kesalahan saat menyimpan data');
            },
            complete: function() {
                submitButton.html(originalContent);
                submitButton.prop("disabled", false);
            }
        });
    });

     $("#btn-delete").on("click", function () {
        let selected = tableDetail.row('.selected').data();

        if (_.isEmpty(selected) ||  selected == undefined) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Pilih Data Terlebih Dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Konfirmasi delete dengan SweetAlert
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim request delete
                $.ajax({
                    url: defaultUrl + "deletedetail/" + selected.id,
                    type: 'POST',
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    success: function(response) {
                        viewDatatable();
                        if(typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Sukses!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    tableDetail.ajax.reload();
                                }
                            });
                        } else {
                            alert(response.message);
                            tableDetail.ajax.reload();
                        }
                        $('#form_sound')[0].reset();
                    },
                    error: function(jqXHR) {
                        let message = 'Terjadi kesalahan saat menghapus data';
                        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            message = jqXHR.responseJSON.message;
                        }
                        Swal.fire('Error!', message, 'error');
                    }
                });
            }
        });
    });

    // Tambahkan event handler untuk tombol close
    $('.close, .btn-secondary').click(function() {
        closeModal();
    });

    // Event handler ketika modal akan ditutup
    $('#formModal').on('hide.bs.modal', function () {
        closeModal();
    });

});

// Fungsi utama untuk update semua perhitungan
function updateAllCalculations() {
    // Update perhitungan tabel utama
    updateSubtotalTotalPrice();
    updateSubtotalPPN();
    updateSubtotalCostPPN();
    updateSubtotalMarginPPN();
    updateSubtotalNonPPN();
    updateSubtotalCostNonPPN();
    updateSubtotalMarginNonPPN();

    // Update perhitungan PO CV
    updateSubtotalPoCV();
    updateSubtotalPoCostCV();
    updateSubtotalPoMarginCV();
    updateSubtotalPersentaseCV();

    // Update perhitungan PPN dan VAT
    updateJumlahPPN();
    updateTotalVat();

    // Update perhitungan cost dan margin
    updateSubtotalCost();
    updateSubtotalValidasiPayment();
    updateTotalMargin();

    // Update perhitungan incentive
    updateIncentiveSales();
    updatePersentaseIncentive();
    updateIncentiveFe001a();

    // Update perhitungan payment
    updatePPHBankFee();

    // Update perhitungan persentase margin
    updateTotalPersentaseMargin();

    // Auto set jenis approve berdasarkan margin
    autoSetJenisApprove();
}

// Fungsi untuk update perhitungan form
function updateFormCalculations() {
    updateTotalPrice();
    updateUnitPriceCV();
    updateTotalPoKeCV();
    updateTotalCost();
    updateMargin();
    updatePersentase();
}

// Fungsi untuk update perhitungan payment
function updatePaymentCalculations() {
    updatePPHBankFee();
    updateIncentiveSales();
    updatePersentaseIncentive();
    updateTotalMargin();
    updateTotalPersentaseMargin();
    autoSetJenisApprove();
}

// Fungsi untuk update perhitungan COGS
function updateCogsCalculations() {
    updateSubtotalCogs();
    updateSubtotalCost();
    updateSubtotalValidasiPayment();
    updateTotalMargin();
    updateIncentiveSales();
    updatePersentaseIncentive();
    updateIncentiveFe001a();
    updateTotalPersentaseMargin();
    autoSetJenisApprove();
}

// Optimasi fungsi updateSubtotalTotalPrice dengan debouncing
let updateTimeout;
function updateSubtotalTotalPrice() {
    clearTimeout(updateTimeout);
    updateTimeout = setTimeout(function() {
        let data = tableDetail.rows().data();
        let subtotal = 0;

        for (let i = 0; i < data.length; i++) {
            let totalPrice = data[i].total_price;
            if (typeof totalPrice === 'string') {
                totalPrice = parseFloat(totalPrice.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalPrice === null || totalPrice === undefined) {
                totalPrice = 0;
            }
            subtotal += totalPrice;
        }

        $('#subtotal-price').val(formatRupiahWithDots(subtotal, ''));
        $('#subtotal-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
    }, 100);
}

// Optimasi fungsi updateSubtotalPPN
function updateSubtotalPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;

    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            let totalCost = data[i].total_po_cv;
            if (typeof totalCost === 'string') {
                totalCost = parseFloat(totalCost.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalCost === null || totalCost === undefined) {
                totalCost = 0;
            }
            subtotal += totalCost;
        }
    }

    $('#subtotal-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

// Optimasi fungsi updateSubtotalCostPPN
function updateSubtotalCostPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;

    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            let totalCost = data[i].total_cost;
            if (typeof totalCost === 'string') {
                totalCost = parseFloat(totalCost.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalCost === null || totalCost === undefined) {
                totalCost = 0;
            }
            subtotal += totalCost;
        }
    }

    $('#subtotal-cost-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

// Optimasi fungsi updateSubtotalMarginPPN
function updateSubtotalMarginPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;

    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            let margin = data[i].margin;
            if (typeof margin === 'string') {
                margin = parseFloat(margin.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (margin === null || margin === undefined) {
                margin = 0;
            }
            subtotal += margin;
        }
    }

    $('#total-margin-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

// Optimasi fungsi updateSubtotalNonPPN
function updateSubtotalNonPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;

    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'non_ppn') {
            let totalPo = data[i].total_po_cv;
            if (typeof totalPo === 'string') {
                totalPo = parseFloat(totalPo.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalPo === null || totalPo === undefined) {
                totalPo = 0;
            }
            subtotal += totalPo;
        }
    }

    $('#subtotal-non-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

// Optimasi fungsi updateSubtotalCostNonPPN
function updateSubtotalCostNonPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;

    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'non_ppn') {
            let totalCost = data[i].total_cost;
            if (typeof totalCost === 'string') {
                totalCost = parseFloat(totalCost.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalCost === null || totalCost === undefined) {
                totalCost = 0;
            }
            subtotal += totalCost;
        }
    }

    $('#subtotal-cost-non-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

// Optimasi fungsi updateSubtotalMarginNonPPN
function updateSubtotalMarginNonPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;

    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'non_ppn') {
            let margin = data[i].margin;
            if (typeof margin === 'string') {
                margin = parseFloat(margin.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (margin === null || margin === undefined) {
                margin = 0;
            }
            subtotal += margin;
        }
    }

    $('#subtotal-margin-non-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

// Optimasi fungsi updateSubtotalPoCV
function updateSubtotalPoCV() {
    let data = tableDetail.rows().data();
    let hasPPN = false;
    let hasNonPPN = false;
    let totalPoCV = 0;

    // Cek apakah ada data PPN dan NON PPN
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            hasPPN = true;
        }
        if (data[i].jenis_ppn === 'non_ppn') {
            hasNonPPN = true;
        }
    }

    // Hitung total berdasarkan jenis PPN
    if (hasPPN && !hasNonPPN) {
        let subtotalPPN = $('#subtotal-ppn').val();
        totalPoCV = parseFloat(subtotalPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    } else if (!hasPPN && hasNonPPN) {
        let subtotalNonPPN = $('#subtotal-non-ppn').val();
        totalPoCV = parseFloat(subtotalNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    } else if (hasPPN && hasNonPPN) {
        let subtotalPPN = $('#subtotal-ppn').val();
        let subtotalNonPPN = $('#subtotal-non-ppn').val();

        let ppnValue = parseFloat(subtotalPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        let nonPpnValue = parseFloat(subtotalNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

        totalPoCV = ppnValue + nonPpnValue;
    }

    $('#subtotal-po-cv').val('Rp ' + formatRupiahWithDots(totalPoCV.toString(), ''));
}

// Optimasi fungsi updateSubtotalPoCostCV
function updateSubtotalPoCostCV() {
    let data = tableDetail.rows().data();
    let hasPPN = false;
    let hasNonPPN = false;
    let totalCostCV = 0;

    // Cek apakah ada data PPN dan NON PPN
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            hasPPN = true;
        }
        if (data[i].jenis_ppn === 'non_ppn') {
            hasNonPPN = true;
        }
    }

    // Hitung total cost berdasarkan jenis PPN
    if (hasPPN && !hasNonPPN) {
        let subtotalCostPPN = $('#subtotal-cost-ppn').val();
        totalCostCV = parseFloat(subtotalCostPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    } else if (!hasPPN && hasNonPPN) {
        let subtotalCostNonPPN = $('#subtotal-cost-non-ppn').val();
        totalCostCV = parseFloat(subtotalCostNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    } else if (hasPPN && hasNonPPN) {
        let subtotalCostPPN = $('#subtotal-cost-ppn').val();
        let subtotalCostNonPPN = $('#subtotal-cost-non-ppn').val();

        let ppnValue = parseFloat(subtotalCostPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        let nonPpnValue = parseFloat(subtotalCostNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

        totalCostCV = ppnValue + nonPpnValue;
    }

    $('#subtotal-po-cost-cv').val('Rp ' + formatRupiahWithDots(totalCostCV.toString(), ''));
}

// Optimasi fungsi updateSubtotalPoMarginCV
function updateSubtotalPoMarginCV() {
    let data = tableDetail.rows().data();
    let hasPPN = false;
    let hasNonPPN = false;
    let totalMarginCV = 0;

    // Cek apakah ada data PPN dan NON PPN
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            hasPPN = true;
        }
        if (data[i].jenis_ppn === 'non_ppn') {
            hasNonPPN = true;
        }
    }

    // Hitung total margin berdasarkan jenis PPN
    if (hasPPN && !hasNonPPN) {
        let subtotalMarginPPN = $('#total-margin-ppn').val();
        totalMarginCV = parseFloat(subtotalMarginPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    } else if (!hasPPN && hasNonPPN) {
        let subtotalMarginNonPPN = $('#subtotal-margin-non-ppn').val();
        totalMarginCV = parseFloat(subtotalMarginNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    } else if (hasPPN && hasNonPPN) {
        let subtotalMarginPPN = $('#total-margin-ppn').val();
        let subtotalMarginNonPPN = $('#subtotal-margin-non-ppn').val();

        let ppnValue = parseFloat(subtotalMarginPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        let nonPpnValue = parseFloat(subtotalMarginNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

        totalMarginCV = ppnValue + nonPpnValue;
    }

    $('#subtotal-margin-cv').val('Rp ' + formatRupiahWithDots(totalMarginCV.toString(), ''));
}

// Optimasi fungsi updateSubtotalPersentaseCV
function updateSubtotalPersentaseCV() {
    let subtotalMarginCV = $('#subtotal-margin-cv').val();
    let subtotalPoCostCV = $('#subtotal-po-cost-cv').val();

    let marginValue = parseFloat(subtotalMarginCV.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    let costValue = parseFloat(subtotalPoCostCV.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

    let persentase = 0;
    if (costValue !== 0) {
        persentase = (marginValue / costValue) * 100;
    }

    $('#subtotal-persentase-cv').val(persentase.toFixed(2) + ' %');
}

// Optimasi fungsi updateJumlahPPN
function updateJumlahPPN() {
    let subtotalPriceText = $('#subtotal-price').val();
    let subtotalPrice = parseFloat(subtotalPriceText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let jumlahPPN = subtotalPrice * 0.11;
    $('#jumlah-ppn').val('Rp ' + formatRupiahWithDots(jumlahPPN.toFixed(0), ''));
}

// Optimasi fungsi updateTotalVat
function updateTotalVat() {
    let subtotalPriceText = $('#subtotal-price').val();
    let jumlahPPNText = $('#jumlah-ppn').val();

    let subtotalPrice = parseFloat(subtotalPriceText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let jumlahPPN = parseFloat(jumlahPPNText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let totalVat = subtotalPrice + jumlahPPN;
    $('#total-vat').val('Rp ' + formatRupiahWithDots(totalVat.toString(), ''));
}

// Optimasi fungsi updateSubtotalCogs
function updateSubtotalCogs() {
    let data = tableCogs.rows().data();
    let subtotal = 0;

    for (let i = 0; i < data.length; i++) {
        let expedittion = parseFloat(data[i].expedittion) || 0;
        let add_insentif = parseFloat(data[i].add_insentif_fe001a) || 0;
        let instalasi_setting = parseFloat(data[i].instalasi_setting) || 0;
        let pph_bank_fee = parseFloat(data[i].pph_bank_fee) || 0;
        let other = parseFloat(data[i].other) || 0;

        subtotal += expedittion + add_insentif + instalasi_setting + pph_bank_fee + other;
    }

    $('#subtotal_cogs').text('Rp ' + formatRupiahWithDots(subtotal.toString(), ''));
}

// Optimasi fungsi updateSubtotalCost
function updateSubtotalCost() {
    let subtotalPoCostCVText = $('#subtotal-po-cost-cv').val();
    let subtotalPoCostCV = parseFloat(subtotalPoCostCVText.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

    let subtotalCogsText = $('#subtotal_cogs').text();
    let subtotalCogs = parseFloat(subtotalCogsText.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

    let totalCost = subtotalPoCostCV + subtotalCogs;
    $('#subtotal_cost').val('Rp ' + formatRupiahWithDots(totalCost.toString(), ''));
}

// Optimasi fungsi updateSubtotalValidasiPayment
function updateSubtotalValidasiPayment() {
    let subtotalText = $('#subtotal-price').val();
    let subtotalPrice = parseFloat(subtotalText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let subtotalCostText = $('#subtotal_cost').val();
    let subtotalCost = parseFloat(subtotalCostText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let subtotalSP2D = subtotalPrice - subtotalCost;
    $('#subtotal_sp2d').val('Rp ' + formatRupiahWithDots(subtotalSP2D.toString(), ''));
}

// Optimasi fungsi updateTotalMargin
function updateTotalMargin() {
    let subtotalPriceText = $('#subtotal-price').val();
    let subtotalPrice = parseFloat(subtotalPriceText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let subtotalSP2DText = $('#subtotal_sp2d').val();
    let subtotalSP2D = parseFloat(subtotalSP2DText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let subtotalCostText = $('#subtotal_cost').val();
    let subtotalCost = parseFloat(subtotalCostText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let totalMarginPercent = 0;

    if (subtotalPrice !== 0 && subtotalCost !== 0) {
        totalMarginPercent = (subtotalSP2D / subtotalCost) * 100;
    }

    $('#total_margin').val(totalMarginPercent.toFixed(2) + ' %');
}

// Optimasi fungsi updateIncentiveSales
function updateIncentiveSales() {
    let totalMarginText = $('#total_margin').val();
    let totalMarginPercent = parseFloat(totalMarginText.replace('%', '')) || 0;

    let subtotalSP2DText = $('#subtotal_sp2d').val();
    let subtotalSP2D = parseFloat(subtotalSP2DText.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

    let incentiveSales = 0;

    if (totalMarginPercent < 10) {
        incentiveSales = subtotalSP2D * 0.0625;
    } else if (totalMarginPercent >= 10 && totalMarginPercent < 15) {
        incentiveSales = subtotalSP2D * 0.125;
    } else if (totalMarginPercent >= 15 && totalMarginPercent < 20) {
        incentiveSales = subtotalSP2D * 0.15;
    } else if (totalMarginPercent >= 20) {
        incentiveSales = subtotalSP2D * 0.20;
    }

    $('#incentive_sales').val('Rp ' + formatRupiahWithDots(incentiveSales.toFixed(0), ''));
}

// Optimasi fungsi updatePersentaseIncentive
function updatePersentaseIncentive() {
    let incentiveSalesText = $('#incentive_sales').val();
    let subtotalSP2DText = $('#subtotal_sp2d').val();

    let incentiveSales = parseFloat((incentiveSalesText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let subtotalSP2D = parseFloat((subtotalSP2DText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let persentase = 0;
    if (subtotalSP2D !== 0) {
        persentase = (incentiveSales / subtotalSP2D) * 100;
    }

    $('#persentase_incentive').val(persentase.toFixed(2) + ' %');
}

// Optimasi fungsi updatePPHBankFee
function updatePPHBankFee() {
    let validasiPaymentText = $('#validasi_payment').val();
    let subTotalvatText = $('#total-vat').val();

    if (!validasiPaymentText || validasiPaymentText.trim() === '') {
        $('#pph_bank_fee').val('-');
        return;
    }

    let validasiPayment = parseFloat(validasiPaymentText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let subTotalvat = parseFloat(subTotalvatText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    if (validasiPayment === subTotalvat) {
        $('#pph_bank_fee').val('-');
    } else {
        let result = subTotalvat - validasiPayment;
        $('#pph_bank_fee').val('Rp ' + formatRupiahWithDots(result.toString(), ''));
    }
}

// Optimasi fungsi updateIncentiveFe001a
function updateIncentiveFe001a() {
    let data = tableCogs.rows().data();
    let addInsentif = 0;

    if (data.length > 0) {
        addInsentif = parseFloat(data[0].add_insentif_fe001a) || 0;
    }

    let incentiveFe001a = addInsentif - (addInsentif * 0.10);
    $('#incentive_fe001a').val('Rp ' + formatRupiahWithDots(incentiveFe001a.toFixed(0), ''));

    let subtotalMarginCVText = $('#subtotal-margin-cv').val();
    let subtotalMarginCV = parseFloat((subtotalMarginCVText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let persentase = 0;
    if (subtotalMarginCV !== 0) {
        persentase = (addInsentif / subtotalMarginCV) * 100;
    }

    if (persentase > 25) {
        $('#persentase_fe001a').val('-');
    } else {
        $('#persentase_fe001a').val(persentase.toFixed(2) + ' %');
    }
}

// Optimasi fungsi updateTotalPersentaseMargin
function updateTotalPersentaseMargin() {
    let subtotalPriceText = $('#subtotal-price').val();
    let subtotalPrice = parseFloat((subtotalPriceText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let subtotalMarginCVText = $('#subtotal_sp2d').val();
    let subtotalPoCostCVText = $('#subtotal_cost').val();

    let subtotalMarginCV = parseFloat((subtotalMarginCVText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let subtotalPoCostCV = parseFloat((subtotalPoCostCVText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let result = '-';

    if (!subtotalPrice || subtotalPrice === 0) {
        result = '0';
    } else if (subtotalPoCostCV !== 0) {
        let persentase = (subtotalMarginCV / subtotalPoCostCV) * 100;
        result = (persentase < 0 ? '-' : '') + Math.abs(persentase).toFixed(2) + ' %';
    }

    $('#total_margin').val(result);

    // Tambahkan pemanggilan fungsi ini
    autoSetJenisApprove();
}

// Optimasi fungsi autoSetJenisApprove
function autoSetJenisApprove() {
    let totalPersentaseMarginText = $('#total_margin').val();
    let persentase = parseFloat((totalPersentaseMarginText || '').replace(/[^0-9\.\-]/g, '')) || 0;

    if (persentase > 6) {
        $('#jenis_approve').val('approve');
    } else {
        $('#jenis_approve').val('need_approve');
    }
}

// ... existing code ...

$('#form-update-ppn').off('submit').on('submit', function(e) {
    e.preventDefault();

    // Prevent double submission
    let submitButton = $(this).find("[type=submit]");
    if (submitButton.prop('disabled')) {
        return false;
    }

    submitButton.prop('disabled', true);
    let originalText = submitButton.text();
    submitButton.text('Menyimpan...');

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(res) {
            Swal.fire('Sukses', 'Data berhasil diupdate!', 'success');
        },
        error: function() {
            Swal.fire('Error', 'Gagal update data', 'error');
        },
        complete: function() {
            submitButton.prop('disabled', false);
            submitButton.text(originalText);
        }
    });
});

$('#form_update_po').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(res) {
            Swal.fire('Sukses', 'Data berhasil diupdate!', 'success');
        },
        error: function() {
            Swal.fire('Error', 'Gagal update data', 'error');
        }
    });
});

$('#form_update_validasi_payment').on('submit', function(e) {
    e.preventDefault();

    let submitButton = $(this).find("[type=submit]");
    let originalContent = submitButton.html();
    submitButton.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...');
    submitButton.prop("disabled", true);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            // Update kolom validasi_payment dan pph_bank_fee jika ada di response
            if (response.validasi_payment !== undefined && response.validasi_payment !== null) {
                $('#validasi_payment').val('Rp ' + formatRupiahWithDots(response.validasi_payment, ''));
            }
            if (response.pph_bank_fee !== undefined) {
                $('#pph_bank_fee').val(
                    response.pph_bank_fee === 0 || response.pph_bank_fee === '-' || response.pph_bank_fee === null
                    ? '-'
                    : 'Rp ' + formatRupiahWithDots(response.pph_bank_fee, '')
                );
            }

            // Update kolom angka jika ada di response
            if (response.angka !== undefined && response.angka !== null) {
                $('#incentive_sales').val('Rp ' + formatRupiahWithDots(response.angka, ''));
            }

            Swal.fire({
                title: 'Sukses',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (typeof tableDetail !== 'undefined') {
                        tableDetail.ajax.reload();
                    }
                    if (typeof tableCogs !== 'undefined') {
                        tableCogs.ajax.reload();
                    }
                }
            });
        },
        error: function(jqXHR) {
            let message = 'Terjadi kesalahan saat mengupdate data';
            if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                message = jqXHR.responseJSON.message;
            }
            Swal.fire('Error!', message, 'error');
        },
        complete: function() {
            submitButton.html(originalContent);
            submitButton.prop("disabled", false);
        }
    });
});

$('#form_update_incentive').off('submit').on('submit', function(e) {
  e.preventDefault();
  $.ajax({
    url: $(this).attr('action'),
    type: 'POST',
    data: $(this).serialize(),
    success: function(res) {
      viewDatatableCogs();
      Swal.fire('Sukses', 'Data berhasil diupdate!', 'success');
    },
    error: function() {
      Swal.fire('Error', 'Gagal update data', 'error');
    }
  });
});

function koleksiSelect2() {
    $('select[name=cmb_vendor]').select2({
        dropdownParent: $('#form_booking'),
        allowClear: true,
        width: '72%',
        // height: '35px',
        placeholder: 'Pilih Nama Vendor',
        ajax: {
            url: "{{ url('/pr_wapu/getvendor') }}",
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.nama_vendor,
                            id: item.id,
                            // jam_keluar: item.jam_keluar,
                            // bayaran: item.bayaran
                        }
                    }),
                    pagination: {
                        more: false
                    }
                };
            },
            cache: true
        }
    });

    $('.select2-container--default .select2-selection--single').css({
        'height': '38px', // Atur tinggi sesuai kebutuhan
        'line-height': '38px' // Atur line-height agar teks terpusat
    });
}


function viewDatatable() {
    tableDetail = $(".basic-datatables").DataTable({
        ajax: {
            url: "{{ route('pr_wapu/datatabledetail') }}",
            "type": "post",
            data: {
                id_projek: "<?php echo $id_projek ?>"
            },
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        dom: 't<"d-flex justify-content-end mt-3"p>',
        pagingType: "simple_numbers",
        "bInfo" : false,
        destroy: true,
        serverSide: true,
        processing: true,
        responsive: true,
        select: {
            style: 'single'
        },
        aaSorting: [],
        columnDefs: [{
            searchable: false,
            targets: [0]
        }],
        columns: [{
                "data": "id_projek",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1 + ".";
                }
            },
            {
                data: "jenis_ppn",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "part_number",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
            data: "partnumber_description",
            className: "text-left align-middle",
            render: function(data, type, row) {
              if (type === 'display') {
                if (data && data.length > 50) {
                  return '<span title="' + data + '">' + data.substring(0, 50) + '...</span>';
                }
                return data;
              }
              return data;
            }
          },
            {
                data: "nama_vendor",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "qty",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "unit_price",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return 'Rp ' + formatRupiahWithDots(data.toString(), '');
                    }
                }
            },
            {
                data: "total_price",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "vendor_price",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "unit_price_cv",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "total_po_cv",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "total_cost",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "margin",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "persentase",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data + ' %';
                    }
                }
            },

        ],createdRow: function (row, data, index) {
            $(row).attr("data-value", encodeURIComponent(JSON.stringify(data)));
            if (data.selected) {
                $(row).addClass('selected');
            }
            $("thead").css({
                "vertical-align": "middle",
                "text-align": "center",
            });
            $("td", row).css({
                "vertical-align": "middle",
                padding: "0.5em",
                cursor: "pointer",
            });
            $("td", row).first().css({
                width: "2%",
                "text-align": "center",
            });
            $("td", row).eq(2).css({
                "text-align": "center",
                "font-weight": "normal",
            });
            $("td", row).eq(3).css({
                "text-align": "center",
                "font-weight": "normal",
            });
            $("td", row).eq(4).css({
                "text-align": "center",
                "font-weight": "normal",
                width: "5%",
            });
            // $("td", row).last().css({ width: "7%", "text-align": "center", });
            //Default
        },
    });

    //    // Handle row selection
    // $('.basic-datatables tbody').on('click', 'tr', function () {
    //     // Toggle select/unselect
    //     if ($(this).hasClass('selected')) {
    //         $(this).removeClass('selected');
    //     } else {
    //         $('.basic-datatables tbody tr').removeClass('selected');
    //         $(this).addClass('selected');
    //     }
    // });


    $('#datatable-cogs tbody').on('click', 'tr', function () {
        // Toggle select/unselect
        if ($(this).hasClass('selected')) {
            // Jika row sudah selected, unselect
            $(this).removeClass('selected');
            $('#btn-edit-cogs').prop('disabled', true);
        } else {
            // Jika row belum selected, select row ini dan unselect yang lain
            $('#datatable-cogs tbody tr').removeClass('selected');
            $(this).addClass('selected');
            $('#btn-edit-cogs').prop('disabled', false);
        }
    });
}

function viewDatatableCogs() {
    tableCogs = $("#datatable-cogs").DataTable({
        ajax: {
            url: "{{ route('pr_wapu/datatablecogs') }}",
            "type": "post",
            data: {
                id_projek: "<?php echo $id_projek ?>"
            },
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        dom: 't<"d-flex justify-content-end mt-3"p>',
        pagingType: "simple_numbers",
        "bInfo" : false,
        destroy: true,
        serverSide: true,
        processing: true,
        responsive: true,
        select: {
            style: 'single'
        },
        aaSorting: [],
        columnDefs: [{
            searchable: false,
            targets: [0]
        }],
        columns: [{
                "data": "id_projek",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1 + ".";
                }
            },
            {
                data: "expedittion",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                       return 'Rp ' + formatRupiahWithDots(data.toString(), '');
                    }
                }
            },
            {
                data: "add_insentif_fe001a",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                       return 'Rp ' + formatRupiahWithDots(data.toString(), '');
                    }
                }
            },
            {
                data: "instalasi_setting",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                       return 'Rp ' + formatRupiahWithDots(data.toString(), '');
                    }
                }
            },
            {
                data: "pph_bank_fee",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return 'Rp ' + formatRupiahWithDots(data.toString(), '');
                    }
                }
            },
            {
                data: "other",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return 'Rp ' + formatRupiahWithDots(data.toString(), '');
                    }
                }
            },
        ],createdRow: function (row, data, index) {
            $(row).attr("data-value", encodeURIComponent(JSON.stringify(data)));
            if (data.selected) {
                $(row).addClass('selected');
            }
            $("thead").css({
                "vertical-align": "middle",
                "text-align": "center",
            });
            $("td", row).css({
                "vertical-align": "middle",
                padding: "0.5em",
                cursor: "pointer",
            });
            $("td", row).first().css({
                width: "2%",
                "text-align": "center",
            });
            $("td", row).eq(2).css({
                "text-align": "center",
                "font-weight": "normal",
            });
            $("td", row).eq(3).css({
                "text-align": "center",
                "font-weight": "normal",
            });
            // $("td", row).last().css({ width: "7%", "text-align": "center", });
            //Default
        },
    });

        // Handle row selection
    $('.basic-datatables tbody').on('click', 'tr', function () {
        // Toggle select/unselect
        if ($(this).hasClass('selected')) {
            // Jika row sudah selected, unselect
            $(this).removeClass('selected');
        } else {
            // Jika row belum selected, select row ini dan unselect yang lain
            $('.basic-datatables tbody tr').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    // Tambahkan event handler untuk enable/disable tombol Add Cogs
    tableCogs.on('draw', function() {
        let dataCount = tableCogs.rows().data().length;

        if (dataCount > 0) {
            // Disable tombol Add Cogs jika sudah ada data
            $('button[data-target="#formCogs"]').prop('disabled', true);

            // Check if any row is selected
            let selectedRow = $('#datatable-cogs tbody tr.selected');
            if (selectedRow.length > 0) {
                // Enable edit button if row is selected
                $('#btn-edit-cogs').prop('disabled', false);
            } else {
                // Disable edit button if no row is selected
                $('#btn-edit-cogs').prop('disabled', true);
            }
        } else {
            // Enable tombol Add Cogs jika belum ada data
            $('button[data-target="#formCogs"]').prop('disabled', false);
            // Disable edit button if no data
            $('#btn-edit-cogs').prop('disabled', true);
        }

        // Update subtotal COGS
        updateSubtotalCogs();
        updateSubtotalCost(); // Tambahkan fungsi ini
        updateSubtotalValidasiPayment(); // Tambahkan fungsi ini
        updateTotalMargin(); // Tambahkan fungsi ini
        updateIncentiveSales(); // Tambahkan fungsi ini
        updateTotalPersentaseMargin();
        updateIncentiveFe001a(); // <-- Tambahkan ini
    });
}

function closeModal() {
    $('#formModal').modal('hide');
    $('#formCogs').modal('hide');
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
        $('#formModal').hide();
        $('#formCogs').hide();
    }, 150);
}

// Tambahkan fungsi helper untuk handle modal
function closeModal() {
    $('#formModal').modal('hide');
    $('#formModal').hide();
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('padding-right', '');
}

// Tambahkan fungsi helper di bagian atas script
function showNotification(type, message) {
    Swal.fire({
        title: type.charAt(0).toUpperCase() + type.slice(1),
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info'
        confirmButtonText: 'OK'
    });
}


function collectionS2Search() {
    $('select[name=cmb_kategori]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '79%',
        placeholder: '',
        ajax: {
            url: "{{ url('/booking/getKategori') }}",
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.nama_kategori,
                            id: item.id
                        }
                    }),
                    pagination: {
                        more: false
                    }
                };
            },
            cache: true
        }
    });

    // Event handler for when kategori changes
    $('select[name=cmb_kategori]').on('select2:select', function (e) {
        // Clear the barang dropdown and enable it
        $('select[name=cmb_barang]').val(null).trigger('change');
        $('select[name=cmb_barang]').prop('disabled', false);

        // Clear the harga field
        $('#harga').val('');
    });

    $('select[name=cmb_barang]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '79%',
        placeholder: '',
        ajax: {
            url: "{{ url('/booking/getBarang') }}",
            dataType: 'json',
            data: function (params) {
                return {
                    id_kategori: $('#cmb_kategori').val(),
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.nama_barang,
                            id: item.id,
                            harga: item.harga,
                            jumlah: item.jumlah,
                        }
                    }),
                    pagination: {
                        more: false
                    }
                };
            },
            cache: true
        }
    });

    $('#jumlah').on('input', function (e) {
        var perkalian = $(this).val();
        var harga = $('#harga_barang').val();

        $('#harga_total').val(parseInt(harga) * parseInt(perkalian));
    });
}

// Fungsi format rupiah dengan titik sebagai pemisah ribuan (tanpa Rp)
function formatRupiahWithDots(angka, prefix = '') {
    if (!angka || angka === null || angka === undefined) {
        return prefix + '0';
    }

    let isNegative = false;
    let number_string = angka.toString();

    if (number_string[0] === '-') {
        isNegative = true;
        number_string = number_string.slice(1);
    }

    number_string = number_string.replace(/[^,\d]/g, '');
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return (isNegative ? '-' : '') + prefix + rupiah;
}

// Helper untuk ambil angka dari input format dengan titik
function getNumberFromDotsFormat(str) {
    return parseFloat(str.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
}

// Event handler untuk semua input yang perlu format dengan titik (tanpa validasi_payment)
$('#Unit_price, #total_price, #vendor_price, #unit_price_cv, #total_po_cv, #total_cost, #margin, #expedittion, #add_insentif_fe001a, #instalasi_setting, #other').on('input', function() {
    let value = $(this).val();
    let numericValue = value.replace(/[^,\d]/g, '').replace(',', '.');
    if (numericValue) {
        $(this).val(formatRupiahWithDots(numericValue, ''));
    } else {
        $(this).val('');
    }
});

// Event handler khusus untuk validasi_payment
$('#validasi_payment').on('input', function() {
    let value = $(this).val();
    let numericValue = value.replace(/[^,\d]/g, '').replace(',', '.');
    if (numericValue) {
        $(this).val(formatRupiahWithDots(numericValue, ''));
    } else {
        $(this).val('');
    }
    // Panggil updatePPHBankFee setelah format
    updatePPHBankFee();
});

// Modifikasi semua fungsi perhitungan agar ambil angka asli dari input
function updateTotalPrice() {
    var qty = parseFloat($('#qty').val()) || 0;
    var unitPrice = getNumberFromDotsFormat($('#Unit_price').val());
    var total = qty * unitPrice;
    $('#total_price').val(formatRupiahWithDots(total.toString(), ''));
}

function updateUnitPriceCV() {
    var vendorPrice = getNumberFromDotsFormat($('#vendor_price').val());
    var unitPriceCV = vendorPrice + (vendorPrice * 0.02);
    $('#unit_price_cv').val(formatRupiahWithDots(unitPriceCV.toFixed(0), ''));
    updateTotalPoKeCV();
}

function updateTotalPoKeCV() {
    var qty = parseFloat($('#qty').val()) || 0;
    var unitPriceCV = getNumberFromDotsFormat($('#unit_price_cv').val());
    var totalPO = qty * unitPriceCV;
    $('#total_po_cv').val(formatRupiahWithDots(totalPO.toString(), ''));
    updateTotalCost();
}

function updateTotalCost() {
    var totalPO = getNumberFromDotsFormat($('#total_po_cv').val());
    var jenisPPN = $('#inputGroupSelect01').val();
    var totalCost;

    if (jenisPPN === 'ppn') {
        totalCost = totalPO / 1.11;
    } else {
        totalCost = totalPO;
    }
    $('#total_cost').val(formatRupiahWithDots(totalCost.toFixed(0), ''));
    updateMargin();
}

// Tambahkan event listener untuk select jenis PPN
$('#inputGroupSelect01').on('change', function() {
    updateTotalCost();
});

function updateMargin() {
    var totalPrice = getNumberFromDotsFormat($('#total_price').val());
    var totalCost = getNumberFromDotsFormat($('#total_cost').val());
    var margin = totalPrice - totalCost;
    $('#margin').val(formatRupiahWithDots(margin.toFixed(0), ''));
    updatePersentase();
}

// Persentase tetap angka biasa
function updatePersentase() {
    var margin = getNumberFromDotsFormat($('#margin').val());
    var totalPO = getNumberFromDotsFormat($('#total_po_cv').val());
    var persentase = 0;
    if (totalPO !== 0) {
        persentase = (margin / totalPO) * 100;
    }
    $('#persentase').val(persentase.toFixed(2));
}

// Event listener tetap sama
$('#qty, #Unit_price').on('input', updateTotalPrice);
$('#vendor_price').on('input', updateUnitPriceCV);
$('#qty').on('input', updateTotalPoKeCV);
$('#total_po_cv').on('input', updateTotalCost);
$('#total_price, #total_cost').on('input', updateMargin);
$('#margin, #total_po_cv').on('input', updatePersentase);

function updateSubtotalTotalPrice() {
    let data = tableDetail.rows().data();
    let subtotal = 0;
    for (let i = 0; i < data.length; i++) {
        let totalPrice = data[i].total_price;
        if (typeof totalPrice === 'string') {
            totalPrice = parseFloat(totalPrice.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
        } else if (totalPrice === null || totalPrice === undefined) {
            totalPrice = 0;
        }
        subtotal += totalPrice;
    }
    $('#subtotal-price').val(formatRupiahWithDots(subtotal, ''));
    $('#subtotal-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

function updateSubtotalPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            let totalCost = data[i].total_po_cv;
            if (typeof totalCost === 'string') {
                totalCost = parseFloat(totalCost.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalCost === null || totalCost === undefined) {
                totalCost = 0;
            }
            subtotal += totalCost;
        }
    }
    $('#subtotal-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

function updateSubtotalCostPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            let totalCost = data[i].total_cost;
            if (typeof totalCost === 'string') {
                totalCost = parseFloat(totalCost.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalCost === null || totalCost === undefined) {
                totalCost = 0;
            }
            subtotal += totalCost;
        }
    }
    $('#subtotal-cost-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

function updateSubtotalMarginPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            let totalCost = data[i].margin;
            if (typeof totalCost === 'string') {
                totalCost = parseFloat(totalCost.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalCost === null || totalCost === undefined) {
                totalCost = 0;
            }
            subtotal += totalCost;
        }
    }
    $('#total-margin-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

function updateSubtotalNonPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'non_ppn') {
            let totalCost = data[i].total_cost;
            if (typeof totalCost === 'string') {
                totalCost = parseFloat(totalCost.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalCost === null || totalCost === undefined) {
                totalCost = 0;
            }
            subtotal += totalCost;
        }
    }
    $('#subtotal-non-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

function updateSubtotalCostNonPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'non_ppn') {
            let totalCost = data[i].total_cost;
            if (typeof totalCost === 'string') {
                totalCost = parseFloat(totalCost.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (totalCost === null || totalCost === undefined) {
                totalCost = 0;
            }
            subtotal += totalCost;
        }
    }
    $('#subtotal-cost-non-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

function updateSubtotalMarginNonPPN() {
    let data = tableDetail.rows().data();
    let subtotal = 0;
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'non_ppn') {
            let margin = data[i].margin;
            if (typeof margin === 'string') {
                margin = parseFloat(margin.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
            } else if (margin === null || margin === undefined) {
                margin = 0;
            }
            subtotal += margin;
        }
    }
    $('#subtotal-margin-non-ppn').val('Rp ' + formatRupiahWithDots(subtotal, ''));
}

// Tambahkan fungsi baru untuk menghitung total PO CV
function updateSubtotalPoCV() {
    let data = tableDetail.rows().data();
    let hasPPN = false;
    let hasNonPPN = false;

    // Cek apakah ada data PPN dan NON PPN
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            hasPPN = true;
        }
        if (data[i].jenis_ppn === 'non_ppn') {
            hasNonPPN = true;
        }
    }

    let totalPoCV = 0;

    // Jika hanya ada PPN, gunakan subtotal PPN
    if (hasPPN && !hasNonPPN) {
        let subtotalPPN = $('#subtotal-ppn').val();
        totalPoCV = parseFloat(subtotalPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    }
    // Jika hanya ada NON PPN, gunakan subtotal NON PPN
    else if (!hasPPN && hasNonPPN) {
        let subtotalNonPPN = $('#subtotal-non-ppn').val();
        totalPoCV = parseFloat(subtotalNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    }
    // Jika ada keduanya, jumlahkan
    else if (hasPPN && hasNonPPN) {
        let subtotalPPN = $('#subtotal-ppn').val();
        let subtotalNonPPN = $('#subtotal-non-ppn').val();

        let ppnValue = parseFloat(subtotalPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        let nonPpnValue = parseFloat(subtotalNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

        totalPoCV = ppnValue + nonPpnValue;
    }

    $('#subtotal-po-cv').val('Rp ' + formatRupiahWithDots(totalPoCV.toString(), ''));
}

function updateSubtotalPoCostCV() {
    let data = tableDetail.rows().data();
    let hasPPN = false;
    let hasNonPPN = false;
    let totalCostCV = 0;

    // Cek apakah ada data PPN dan NON PPN
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            hasPPN = true;
        }
        if (data[i].jenis_ppn === 'non_ppn') {
            hasNonPPN = true;
        }
    }

    // Hitung total cost berdasarkan jenis PPN
    if (hasPPN && !hasNonPPN) {
        let subtotalCostPPN = $('#subtotal-cost-ppn').val();
        totalCostCV = parseFloat(subtotalCostPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    } else if (!hasPPN && hasNonPPN) {
        let subtotalCostNonPPN = $('#subtotal-cost-non-ppn').val();
        totalCostCV = parseFloat(subtotalCostNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    } else if (hasPPN && hasNonPPN) {
        let subtotalCostPPN = $('#subtotal-cost-ppn').val();
        let subtotalCostNonPPN = $('#subtotal-cost-non-ppn').val();

        let ppnValue = parseFloat(subtotalCostPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        let nonPpnValue = parseFloat(subtotalCostNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

        totalCostCV = ppnValue + nonPpnValue;
    }

    $('#subtotal-po-cost-cv').val('Rp ' + formatRupiahWithDots(totalCostCV.toString(), ''));
}

function updateSubtotalPoMarginCV() {
    let data = tableDetail.rows().data();
    let hasPPN = false;
    let hasNonPPN = false;

    // Cek apakah ada data PPN dan NON PPN
    for (let i = 0; i < data.length; i++) {
        if (data[i].jenis_ppn === 'ppn') {
            hasPPN = true;
        }
        if (data[i].jenis_ppn === 'non_ppn') {
            hasNonPPN = true;
        }
    }

    let totalMarginCV = 0;

    // Jika hanya ada PPN, gunakan subtotal margin PPN
    if (hasPPN && !hasNonPPN) {
        let subtotalMarginPPN = $('#total-margin-ppn').val();
        totalMarginCV = parseFloat(subtotalMarginPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    }
    // Jika hanya ada NON PPN, gunakan subtotal margin NON PPN
    else if (!hasPPN && hasNonPPN) {
        let subtotalMarginNonPPN = $('#subtotal-margin-non-ppn').val();
        totalMarginCV = parseFloat(subtotalMarginNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    }
    // Jika ada keduanya, jumlahkan
    else if (hasPPN && hasNonPPN) {
        let subtotalMarginPPN = $('#total-margin-ppn').val();
        let subtotalMarginNonPPN = $('#subtotal-margin-non-ppn').val();

        let ppnValue = parseFloat(subtotalMarginPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        let nonPpnValue = parseFloat(subtotalMarginNonPPN.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

        totalMarginCV = ppnValue + nonPpnValue;
    }

    $('#subtotal-margin-cv').val('Rp ' + formatRupiahWithDots(totalMarginCV.toString(), ''));
}

function updateSubtotalPersentaseCV() {
    let subtotalMarginCV = $('#subtotal-margin-cv').val();
    let subtotalPoCostCV = $('#subtotal-po-cost-cv').val();

    let marginValue = parseFloat(subtotalMarginCV.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    let costValue = parseFloat(subtotalPoCostCV.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

    let persentase = 0;
    if (costValue !== 0) {
        persentase = (marginValue / costValue) * 100;
    }

    $('#subtotal-persentase-cv').val(persentase.toFixed(2) + ' %');
}

function updateJumlahPPN() {
    let subtotalPriceText = $('#subtotal-price').val();
    let subtotalPrice = parseFloat(subtotalPriceText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let jumlahPPN = subtotalPrice * 0.11;
    $('#jumlah-ppn').val('Rp ' + formatRupiahWithDots(jumlahPPN.toFixed(0), ''));
}

function updateTotalVat() {
    let subtotalPriceText = $('#subtotal-price').val();
    let jumlahPPNText = $('#jumlah-ppn').val();

    let subtotalPrice = parseFloat(subtotalPriceText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let jumlahPPN = parseFloat(jumlahPPNText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let totalVat = subtotalPrice + jumlahPPN;
    $('#total-vat').val('Rp ' + formatRupiahWithDots(totalVat.toString(), ''));
}

function updateSubtotalCogs() {
    let data = tableCogs.rows().data();
    let subtotal = 0;

    for (let i = 0; i < data.length; i++) {
        // Hitung total dari semua field COGS
        let expedittion = parseFloat(data[i].expedittion) || 0;
        let add_insentif = parseFloat(data[i].add_insentif_fe001a) || 0;
        let instalasi_setting = parseFloat(data[i].instalasi_setting) || 0;
        let pph_bank_fee = parseFloat(data[i].pph_bank_fee) || 0;
        let other = parseFloat(data[i].other) || 0;

        subtotal += expedittion + add_insentif + instalasi_setting + pph_bank_fee + other;
    }

    $('#subtotal_cogs').text('Rp ' + formatRupiahWithDots(subtotal.toString(), ''));
}

function updateSubtotalCost() {
    // Ambil nilai dari subtotal-po-cost-cv
    let subtotalPoCostCVText = $('#subtotal-po-cost-cv').val();
    let subtotalPoCostCV = parseFloat(subtotalPoCostCVText.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

    // Ambil nilai dari subtotal_cogs
    let subtotalCogsText = $('#subtotal_cogs').text();
    let subtotalCogs = parseFloat(subtotalCogsText.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

    // Hitung total
    let totalCost = subtotalPoCostCV + subtotalCogs;

    // Update elemen subtotal_cost
    $('#subtotal_cost').val('Rp ' + formatRupiahWithDots(totalCost.toString(), ''));
}

function updateSubtotalValidasiPayment() {
    // Ambil nilai dari subtotal-price
    let subtotalText = $('#subtotal-price').val();
    let subtotalPrice = parseFloat(subtotalText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    // Ambil nilai dari subtotal_cost
    let subtotalCostText = $('#subtotal_cost').val();
    let subtotalCost = parseFloat(subtotalCostText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    // Hitung selisih: subtotal-price - subtotal_cost
    let subtotalSP2D = subtotalPrice - subtotalCost;

    // Update elemen subtotal_sp2d
    $('#subtotal_sp2d').val('Rp ' + formatRupiahWithDots(subtotalSP2D.toString(), ''));

    // Panggil fungsi untuk update total margin
    updateTotalMargin();
}

// Tambahkan fungsi baru untuk menghitung total margin
function updateTotalMargin() {
    // Ambil nilai subtotal-price
    let subtotalPriceText = $('#subtotal-price').val();
    let subtotalPrice = parseFloat(subtotalPriceText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    // Ambil nilai subtotal_sp2d
    let subtotalSP2DText = $('#subtotal_sp2d').val();
    let subtotalSP2D = parseFloat(subtotalSP2DText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    // Ambil nilai subtotal_cost
    let subtotalCostText = $('#subtotal_cost').val();
    let subtotalCost = parseFloat(subtotalCostText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let totalMarginPercent = 0;

    // Kondisi: jika subtotal-price tidak sama dengan 0
    if (subtotalPrice !== 0 && subtotalCost !== 0) {
        // Hitung persentase: (subtotal_sp2d / subtotal_cost) * 100
        totalMarginPercent = (subtotalSP2D / subtotalCost) * 100;
    }

    // Format output: 2 desimal + spasi + %
    $('#total_margin').val(totalMarginPercent.toFixed(2) + ' %');

    // Panggil fungsi untuk update incentive sales
    updateIncentiveSales();
}

// Tambahkan fungsi baru untuk menghitung incentive sales
function updateIncentiveSales() {
    // Ambil nilai total_margin (dalam persen)
    let totalMarginText = $('#total_margin').val();
    let totalMarginPercent = parseFloat(totalMarginText.replace('%', '')) || 0;

    // Ambil nilai subtotal_sp2d
    let subtotalSP2DText = $('#subtotal_sp2d').val();
    let subtotalSP2D = parseFloat(subtotalSP2DText.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;

    let incentiveSales = 0;

    // Kondisi perhitungan incentive sales berdasarkan total_margin
    if (totalMarginPercent < 10) {
        // Jika total_margin < 10%, maka subtotal_sp2d * 6.25%
        incentiveSales = subtotalSP2D * 0.0625;
    } else if (totalMarginPercent >= 10 && totalMarginPercent < 15) {
        // Jika total_margin >= 10% dan < 15%, maka subtotal_sp2d * 12.5%
        incentiveSales = subtotalSP2D * 0.125;
    } else if (totalMarginPercent >= 15 && totalMarginPercent < 20) {
        // Jika total_margin >= 15% dan < 20%, maka subtotal_sp2d * 15%
        incentiveSales = subtotalSP2D * 0.15;
    } else if (totalMarginPercent >= 20) {
        // Jika total_margin >= 20%, maka subtotal_sp2d * 20%
        incentiveSales = subtotalSP2D * 0.20;
    }

    // Update elemen angka
    $('#incentive_sales').val('Rp ' + formatRupiahWithDots(incentiveSales.toFixed(0), ''));
    updatePersentaseIncentive(); // <--- Tambahkan ini
}

function updatePersentaseIncentive() {
    let incentiveSalesText = $('#incentive_sales').val();
    let subtotalSP2DText = $('#subtotal_sp2d').val();

    let incentiveSales = parseFloat((incentiveSalesText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let subtotalSP2D = parseFloat((subtotalSP2DText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let persentase = 0;
    if (subtotalSP2D !== 0) {
        persentase = (incentiveSales / subtotalSP2D) * 100;
    }

    $('#persentase_incentive').val(persentase.toFixed(2) + ' %');
}

function updatePPHBankFee() {
    let validasiPaymentText = $('#validasi_payment').val();
    let subTotalvatText = $('#total-vat').val();

    if (!validasiPaymentText || validasiPaymentText.trim() === '') {
        $('#pph_bank_fee').val('-');
        return;
    }

    let validasiPayment = parseFloat(validasiPaymentText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let subTotalvat = parseFloat(subTotalvatText.replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    if (validasiPayment === subTotalvat) {
        $('#pph_bank_fee').val('-');
    } else {
        let result = subTotalvat - validasiPayment;
        $('#pph_bank_fee').val('Rp ' + formatRupiahWithDots(result.toString(), ''));
    }
}

function updateTotalPersentaseMargin() {
    let subtotalPriceText = $('#subtotal-price').val();
    let subtotalPrice = parseFloat((subtotalPriceText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let subtotalMarginCVText = $('#subtotal_sp2d').val();
    let subtotalPoCostCVText = $('#subtotal_cost').val();

    let subtotalMarginCV = parseFloat((subtotalMarginCVText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;
    let subtotalPoCostCV = parseFloat((subtotalPoCostCVText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    let result = '-';

    if (!subtotalPrice || subtotalPrice === 0) {
        result = '0';
    } else if (subtotalPoCostCV !== 0) {
        let persentase = (subtotalMarginCV / subtotalPoCostCV) * 100;
        result = (persentase < 0 ? '-' : '') + Math.abs(persentase).toFixed(2) + ' %';
    }

    $('#total_margin').val(result);

    // Tambahkan pemanggilan fungsi ini
    autoSetJenisApprove();
}

// Tambahkan fungsi berikut:
function autoSetJenisApprove() {
    let totalPersentaseMarginText = $('#total_margin').val();
    let persentase = parseFloat((totalPersentaseMarginText || '').replace(/[^0-9\.\-]/g, '')) || 0;

    if (persentase > 6) {
        $('#jenis_approve').val('approve');
    } else {
        $('#jenis_approve').val('need_approve');
    }
}

// Pastikan fungsi ini dipanggil setiap kali data berubah
tableDetail.on('draw', function() {
    // ...fungsi update lain...
    updateTotalPersentaseMargin();
    updateSubtotalMarginPPN();
});
tableCogs.on('draw', function() {
    // ...fungsi update lain...
    updateTotalPersentaseMargin();
    updateIncentiveFe001a(); // <-- Tambahkan ini
});

function updateIncentiveFe001a() {
    // Ambil data dari tabel COGS
    let data = tableCogs.rows().data();
    let addInsentif = 0;

    if (data.length > 0) {
        addInsentif = parseFloat(data[0].add_insentif_fe001a) || 0;
    }

    // Kurangi 10% untuk incentive fe001a
    let incentiveFe001a = addInsentif - (addInsentif * 0.10);
    $('#incentive_fe001a').val('Rp ' + formatRupiahWithDots(incentiveFe001a.toFixed(0), ''));

    // Ambil nilai subtotal-margin-cv
    let subtotalMarginCVText = $('#subtotal-margin-cv').val();
    let subtotalMarginCV = parseFloat((subtotalMarginCVText || '').replace(/[^-\d,]/g, '').replace(',', '.')) || 0;

    // Hitung persentase
    let persentase = 0;
    if (subtotalMarginCV !== 0) {
        persentase = (addInsentif / subtotalMarginCV) * 100;
    }

    // Update kolom persentase_fe001a sesuai kondisi
    if (persentase > 25) {
        $('#persentase_fe001a').val('need approve');
    } else {
        $('#persentase_fe001a').val(persentase.toFixed(2) + ' %');
    }
}

// Jika ada input manual:
$('#expedittion, #add_insentif_fe001a, #instalasi_setting, #pph_bank_fee, #other').on('input', function() {
    updateSubtotalCogs();
    updateSubtotalCost();
});

// Event listener untuk update real time Total Margin PPN
$('#qty, #Unit_price, #vendor_price, #unit_price_cv, #total_po_cv, #total_cost, #margin').on('input', function() {
    updateMargin();
    updateSubtotalMarginPPN();
});

// Tambahkan event listener agar total_margin update secara real time
$('#subtotal-price, #subtotal_cost, #subtotal_sp2d, #total_margin').on('input', function() {
    updateTotalMargin();
});

// Jika ada perubahan manual pada input subtotal, pastikan margin ikut update
$('#subtotal-price, #subtotal_cost, #subtotal_sp2d').on('change', function() {
    updateTotalMargin();
});

</script>
  @endpush


@endsection
