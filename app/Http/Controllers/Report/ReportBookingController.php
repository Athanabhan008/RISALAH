<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Libraries\easyTables;
use App\Libraries\exFPDF;

class ReportBookingController extends Controller
{
    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new exFPDF('P', 'cm', 'A4');
    }

    function array_group_by(array $array, $key)
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);
            return null;
        }

        $func = (!is_string($key) && is_callable($key) ? $key : null);
        $_key = $key;

        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($array as $value) {
            $key = null;

            if (is_callable($func)) {
                $key = call_user_func($func, $value);
            } elseif (is_object($value) && property_exists($value, $_key)) {
                $key = $value->{$_key};
            } elseif (isset($value[$_key])) {
                $key = $value[$_key];
            }

            if ($key === null) {
                continue;
            }

            $grouped[$key][] = $value;
        }

        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();

            foreach ($grouped as $key => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $params);
            }
        }

        return $grouped;
    }

    function numToBulan($bulan)
    {
        if ($bulan == 1) $bulan = 'Januari';
        else if ($bulan == 2) $bulan = 'Februari';
        else if ($bulan == 3) $bulan = 'Maret';
        else if ($bulan == 4) $bulan = 'April';
        else if ($bulan == 5) $bulan = 'Mei';
        else if ($bulan == 6) $bulan = 'Juni';
        else if ($bulan == 7) $bulan = 'Juli';
        else if ($bulan == 8) $bulan = 'Agustus';
        else if ($bulan == 9) $bulan = 'September';
        else if ($bulan == 10) $bulan = 'Oktober';
        else if ($bulan == 11) $bulan = 'November';
        else if ($bulan == 12) $bulan = 'Desember';
        return $bulan;
    }

    public function index()
    {
        return view ('report.booking', [
            "active" => 'report'
        ]);
    }

    public function createPdfRekapBookingPerBulan(Request $request)
    {
        $periode = str_replace('-', '', $request->periode);
        $tahun = substr($periode, 0, 4);
        $bulan = substr($periode, 4, 6);

        $sql = DB::select("CALL sp_lap_booking_penjualanperbulan($periode)");
        $data_result = $this->array_group_by($sql, 'nama');
        // echo "<pre>";
        // print_r($data_result);die;



    	$this->fpdf->SetFont('Arial', '', 12);
        $this->fpdf->AddPage();

        $judul = new easyTables($this->fpdf, 1, 'border:0;font-size:12;  font-style:R;');

		$judul->easyCell('ARGANA', 'valign:M;align:C;font-style:B;');
		$judul->printRow();

		$judul->easyCell('LAPORAN PENJUALAN PER BULAN', 'valign:M;align:C;font-style:B;');
		$judul->printRow();

		$judul->easyCell('PERIODE '. strtoupper($this->numToBulan($bulan)) . ' ' . $tahun, 'valign:M;align:C;font-style:B;');
		$judul->printRow();

		$judul->endTable(0);
        $this->fpdf->Ln(1);




        $table = new easyTables($this->fpdf, "{5, 15, 8, 8, 12, 15}", 'border:1;font-size:9;');

        $table->rowStyle('font-style:B');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('NAMA CLIENT', 'valign:M;align:C;');
        $table->easyCell('NO HANDPHONE', 'valign:M;align:C;');
        $table->easyCell('BARANG', 'valign:M;align:C;');
        $table->easyCell('TGL BOOKING', 'valign:M;align:C;');
        $table->easyCell('HARGA', 'valign:M;align:C;');
        $table->printRow();
        
        $no = 1;
        foreach ($data_result as $client => $row) {

            $table->rowStyle('font-style:B');
            $table->easyCell($no, 'valign:M;align:C;');
            $table->easyCell($client, 'colspan:5;valign:M;align:L;');
            $table->printRow();
    
            $i = 1;
            foreach ($row as $value) {
                $table->easyCell('', 'valign:M;align:C;');
                $table->easyCell( $no. '.'. $i++ . ' ' . $value->nama, 'valign:M;align:L;');
                $table->easyCell('-', 'valign:M;align:C;');
                $table->easyCell($value->barang, 'valign:M;align:L;');
                $table->easyCell($value->tgl, 'valign:M;align:C;');
                $table->easyCell('Rp ' . number_format($value->harga, 0, ',', '.'), 'valign:M;align:R;');            $table->printRow();
            }
    
            $table->rowStyle('font-style:B');
            $table->easyCell('TOTAL', 'colspan:5;valign:M;align:R;');
            $table->easyCell('Rp ' . number_format(array_sum(array_column($row, 'harga')), 0, ',', '.'), 'valign:M;align:R;');
            $table->printRow();

            $no++;
            // $this->fpdf->Ln(1);
        }
        $this->fpdf->Ln(1);

        $grand_total = 0;
        foreach ($data_result as $value) {
            foreach ($value as $key => $data) {
                $grand_total += $data->harga;
            }
        }

        $table->rowStyle('font-style:B');
        $table->easyCell('TOTAL KESELURUHAN', 'colspan:5;valign:M;align:L;');
        $table->easyCell('Rp ' . number_format($grand_total, 0, ',', '.'), 'valign:M;align:R;');
        $table->printRow();


		$table->endTable(0);


        $this->fpdf->Output();
        exit;
    }
}
