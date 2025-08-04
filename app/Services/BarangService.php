<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\StokBarang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BarangService
{
    /**
     * Menyimpan data barang dan stok sekaligus
     */
    public function createBarangWithStok(array $data)
    {
        try {
            DB::beginTransaction();

            // Validasi data
            $this->validateBarangData($data);

            // Simpan data barang
            $barang = $this->createBarang($data);

            // Simpan data stok
            $stokBarang = $this->createStokBarang($barang->id, $data);

            DB::commit();

            return [
                'success' => true,
                'barang' => $barang,
                'stok' => $stokBarang
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating barang with stok: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Membuat data barang
     */
    private function createBarang(array $data)
    {
        $barang = new Barang();
        $barang->nama_barang = $data['nama_barang'];
        $barang->id_kategori = $data['id_kategori'];
        $barang->harga = $data['harga'];
        $barang->tgl_masuk = $data['tgl_masuk'];
        $barang->save();

        return $barang;
    }

    /**
     * Membuat data stok barang
     */
    private function createStokBarang(int $barangId, array $data)
    {
        $stokBarang = new StokBarang();
        $stokBarang->id_barang = $barangId;
        $stokBarang->stok = $data['stok_awal'];
        $stokBarang->keterangan = $data['keterangan_stok'] ?? 'Stok awal';
        $stokBarang->tanggal = date('Y-m-d');
        $stokBarang->save();

        return $stokBarang;
    }

    /**
     * Validasi data barang
     */
    private function validateBarangData(array $data)
    {
        if (empty($data['nama_barang'])) {
            throw new \Exception('Nama barang harus diisi');
        }

        if (empty($data['id_kategori'])) {
            throw new \Exception('Kategori harus dipilih');
        }

        if (!is_numeric($data['harga']) || $data['harga'] < 0) {
            throw new \Exception('Harga harus berupa angka positif');
        }

        if (empty($data['tgl_masuk'])) {
            throw new \Exception('Tanggal masuk harus diisi');
        }

        if (!is_numeric($data['stok_awal']) || $data['stok_awal'] < 0) {
            throw new \Exception('Stok awal harus berupa angka positif');
        }
    }

    /**
     * Update data barang dan stok
     */
    public function updateBarangWithStok(int $barangId, array $data)
    {
        try {
            DB::beginTransaction();

            // Update data barang
            $barang = Barang::findOrFail($barangId);
            $barang->nama_barang = $data['nama_barang'];
            $barang->id_kategori = $data['id_kategori'];
            $barang->harga = $data['harga'];
            $barang->tgl_masuk = $data['tgl_masuk'];
            $barang->save();

            // Update atau buat stok baru
            $stokBarang = StokBarang::where('id_barang', $barangId)->first();
            if (!$stokBarang) {
                $stokBarang = new StokBarang();
                $stokBarang->id_barang = $barangId;
            }

            $stokBarang->stok = $data['stok_awal'];
            $stokBarang->keterangan = $data['keterangan_stok'] ?? 'Update stok';
            $stokBarang->tanggal = date('Y-m-d');
            $stokBarang->save();

            DB::commit();

            return [
                'success' => true,
                'barang' => $barang,
                'stok' => $stokBarang
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating barang with stok: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
