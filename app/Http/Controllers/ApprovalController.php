<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\SharingProfit;
use App\Models\SharingProfitModel;
use App\Models\VwSharingprofit;
use App\Models\Wapu;
use Illuminate\Support\Facades\Log;

class ApprovalController extends Controller
{
    public function index()
    {

        $user = auth()->user()->toArray();
        $today = date('Y-m-d');
        $month = date('Ym');
        $year = date('Y');


        //BOOKING

        return view('approval.index', [
            "active" => 'approval',
            "user" => $user
        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        // Dukungan filter: range bulan (prioritas) atau 1 bulan (kompatibilitas lama)
        $periodeStart = request()->get('periode_start');
        $periodeEnd = request()->get('periode_end');
        $periodeSingle = request()->get('periode_pr');

        $user = auth()->user();
        $query = VwSharingprofit::query();

        if ($periodeStart && $periodeEnd) {
            $query->whereBetween('periode', [$periodeStart, $periodeEnd]);
        } elseif ($periodeSingle) {
            $query->where('periode', $periodeSingle);
        }

        if ($id_user) {
            $query->where('nip_user', $id_user);
        }

        // Filter berdasarkan user yang login
        if (!in_array($user->role, ['super_admin', 'admin', 'manager'])) {
            $query->where('id_sales', $user->id);
        }

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

    public function setApprove(Request $request)
    {

        $user = auth()->user()->toArray();

        try {

            $id_user = $user['id'];
            $nama_user = $user['name'];

            // Dukungan rentang bulan atau 1 bulan
            $periodeStart = $request->input('periode_start');
            $periodeEnd = $request->input('periode_end');
            $periodeSingle = $request->input('periode_pr');

            if ($periodeStart && $periodeEnd) {
                $yearS = substr($periodeStart, 0, 4);
                $monthS = substr($periodeStart, 4, 2);
                $yearE = substr($periodeEnd, 0, 4);
                $monthE = substr($periodeEnd, 4, 2);

                $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$yearS-$monthS")->startOfMonth();
                $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$yearE-$monthE")->endOfMonth();
            } elseif ($periodeSingle) {
                $year = substr($periodeSingle, 0, 4);
                $month = substr($periodeSingle, 4, 2);

                $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->startOfMonth();
                $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->endOfMonth();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Periode belum dipilih'
                ], 422);
            }

            $prwapusData = Wapu::whereBetween('created_at', [$startDate, $endDate])->get();

            foreach ($prwapusData as $prwapu) {
                $sharingProfit = SharingProfitModel::where('id_projek', $prwapu->id)->first();

                if ($sharingProfit) {

                    if ($user['role'] == 'admin') {
                        $sharingProfit->update([
                            'is_pengajuan_admin' => 1,
                            'id_admin' => $id_user,
                            'nama_admin' => $nama_user
                        ]);
                    } else {
                        $sharingProfit->update([
                            'is_approve' => 1,
                            'id_approve' => $id_user,
                            'user_approve' => $nama_user
                        ]);
                    }

                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $sharingProfit ?? null
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
