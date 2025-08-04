@extends('layouts.manager.template_manager')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Tambah Data Barang dengan Stok</h6>
                </div>
                <div class="card-body">
                    <form id="formBarangStok" method="POST">
                        @csrf

                        <!-- Data Barang -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_barang" class="form-control-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_kategori" class="form-control-label">Kategori</label>
                                    <select class="form-control" id="id_kategori" name="id_kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga" class="form-control-label">Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_masuk" class="form-control-label">Tanggal Masuk</label>
                                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required>
                                </div>
                            </div>
                        </div>

                        <!-- Data Stok -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok_awal" class="form-control-label">Stok Awal</label>
                                    <input type="number" class="form-control" id="stok_awal" name="stok_awal" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan_stok" class="form-control-label">Keterangan Stok</label>
                                    <input type="text" class="form-control" id="keterangan_stok" name="keterangan_stok" placeholder="Stok awal">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                                <a href="{{ route('manager.barang.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Set tanggal hari ini sebagai default
    $('#tgl_masuk').val(new Date().toISOString().split('T')[0]);

    $('#formBarangStok').on('submit', function(e) {
        e.preventDefault();

        // Disable submit button
        $('button[type="submit"]').prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: '{{ route("manager.barang.save-with-stok") }}',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Tampilkan notifikasi sukses
                    alert(response.message);

                    // Redirect ke halaman index
                    window.location.href = '{{ route("manager.barang.index") }}';
                } else {
                    alert('Gagal menyimpan data: ' + response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan sistem';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert('Error: ' + errorMessage);
            },
            complete: function() {
                // Enable submit button
                $('button[type="submit"]').prop('disabled', false).text('Simpan Data');
            }
        });
    });
});
</script>
@endsection
