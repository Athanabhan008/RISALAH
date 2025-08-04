<?php

namespace App\Http\Controllers\booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\VwBarang;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriBarang;
use Illuminate\Validation\ValidationException;
use App\Models\Booking;
use App\Models\VwBookingdetail;
use App\Models\Kategori;
use App\Models\BookingDetail;

class BookingController extends Controller
{
    public function index()
    {
        $booking = Booking::all();
        return view('booking.index', [
            'booking' => $booking,
            "active" => 'booking'
        ]);
    }


    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');

        $query = Booking::query();

        $total = $query->count();

        // Apply pagination
        $results = $query->offset($start)
                        ->limit($length)
                        ->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $results
        ]);
    }

    public function create(Request $request)
    {
        try {
            $data_booking = new Booking();
            $data_booking->nama_client = $request->nama_client;
            $data_booking->tgl_booking = $request->tgl_booking;
            $data_booking->updated_at = null;
            $data_booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $data_booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $data_booking = Booking::findOrFail($id);
            $data_booking->nama_client = $request->nama_client;
            $data_booking->tgl_booking = $request->tgl_booking;
            $data_booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $data_booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $data_booking = Booking::findOrFail($id);
            $data_booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function proses_tambah_data_booking()
    {

    }


    public function ubah_data_booking()
    {
        return view('booking.ubah_data_booking');
    }


    public function proses_ubah_data_booking()
    {
        return view('booking.ubah_data_booking');
    }


    public function detail_data_booking(Request $request)
    {
        // $query = VwBookingdetail::query();
        // $query->where('id_booking', $id_booking);

        return view('booking.detail_data_booking', [
            'active'=> 'booking',
            'id_booking' => $request->id_booking
        ]);
    }

    public function datatabledetail(Request $request,)
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_booking = request()->get('id_booking');

        $query = VwBookingdetail::query();
        $query->where('id_booking', $id_booking);

        $total = $query->count();

        // Apply pagination
        $results = $query->offset($start)
                        ->limit($length)
                        ->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $results
        ]);
    }

    public function detailCreate(Request $request)
    {
        try {

            $id_booking = $request->id_booking;
            $cmb_kategori = $request->cmb_kategori;
            $cmb_barang = $request->cmb_barang;
            $jumlah = $request->jumlah;
            $harga_total = $request->harga_total;


            $sql = DB::select("CALL sp_booking_detailbarang_insert(
                $id_booking,
                $cmb_kategori,
                $cmb_barang,
                $jumlah,
                $harga_total,

                'Booking',
                'Pengurangan'
            )");
            $stts = $sql[0]->stts;


            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'query' => $sql,
                'data' => $stts
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detailUpdate(Request $request)
    {
        try {
            $result = BookingDetail::where('id_booking', $request->id_booking)
                              ->where('id', $request->id)
                              ->firstOrFail();

            // Update the booking detail
            $result->id_kategori = $request->cmb_kategori;
            $result->id_barang = $request->cmb_barang;
            $result->jumlah = $request->jumlah;
            $result->harga = $request->harga_total;
            $result->updated_at = now();
            $result->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detailDelete(Request $request)
    {
        try {
            $result = BookingDetail::where('id_booking', $request->id_booking)
                              ->where('id', $request->id)
                              ->firstOrFail();
            $result->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
                'data'=> $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }





    public function getBarangByKategori($kategori_id)
    {
        $barang = VwBarang::where('id_kategori', $kategori_id)->get()->toArray();
        return response()->json($barang);
    }

    public function getKategori()
    {
        $result = Kategori::all();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }

    public function getBarang()
    {
        $id_kategori = request()->get('id_kategori');

        $query = VwBarang::query();
        $query->where('id_kategori', $id_kategori);

        // Apply pagination
        $result = $query->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }

}
