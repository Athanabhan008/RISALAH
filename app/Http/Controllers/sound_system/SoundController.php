<?php

namespace App\Http\Controllers\sound_system;

use App\Models\VWsound;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaketSoundsystem;
use App\Models\SoundSystem;
use App\Models\DetailSoundSystem;

class SoundController extends Controller
{
    public function index()
    {
        return view('sound_system.index');
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');

        $query = VWsound::query();

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
            $data_sound = new SoundSystem();
            $data_sound->id_paket_sound = $request->cmb_sound;
            $data_sound->nama_client = $request->nama_client;
            $data_sound->tgl_booking = $request->tgl_booking;
            $data_sound->nama_paket = $request->nama_paket;
            $data_sound->total_harga = $request->total_harga;
            $data_sound->updated_at = null;
            $data_sound->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $data_sound
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

            $data_sound = SoundSystem::findOrFail($id);
            $data_sound->id_paket_sound = $request->id_paket_sound;
            $data_sound->nama_client = $request->nama_client;
            $data_sound->nama_paket = $request->nama_paket;
            $data_sound->total_harga = $request->total_harga;
            $data_sound->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $data_sound
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
            $data_sound = SoundSystem::findOrFail($id);
            $data_sound->delete();

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


    public function tambah_data_sound()
    {
        $paket_sound = PaketSoundsystem::all();
        return view('sound_system.tambah_data_sound', compact('paket_sound'));
    }
    public function proses_tambah_sound(Request $request)
    {

        $array_paket = array_map(function($id) {
            return ['id_paket' => trim($id)];
        }, explode(',', $request->paket_sound));


        $laundry                            =new SoundSystem();
        $laundry->nama_client               =$request->nama_peserta;
        $laundry->save();
        $insertedId = $laundry->id;

        foreach ($array_paket as $value) {
            $paket_sound = PaketSoundsystem::where('id', $value['id_paket'])->get()->toArray();

            $laundry                            =new DetailSoundSystem();
            $laundry->id_booking                =$insertedId;
            $laundry->nama_paket                =$paket_sound[0]['nama_paket'];
            $laundry->nama_client               =$request->nama_peserta;
            $laundry->total_harga               =$paket_sound[0]['harga'];
            $laundry->save();
        }

        return back();
    }

    public function getpaketsound()
    {

        $query = PaketSoundsystem::query();

        // Apply pagination
        $result = $query->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }
}
