<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\easyTables;
use Illuminate\Support\Facades\DB;
use App\Libraries\exFPDF;

class ReportSoundController extends Controller
{
    protected $fpdf;
    public function index()
    {
        return view ('report.sound_system', [
            "active" => 'report'
        ]);
    }
    public function __construct()
    {
        $this->fpdf = new exFPDF('P', 'mm', 'A4');
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

    public function createPdfRekapSoundPerBulan(Request $request)
    {
        $periode = str_replace('-', '', $request->periode);
        $tahun = substr($periode, 0, 4);
        $bulan = substr($periode, 4, 6);

        $data_result = DB::select("CALL sp_lap_sound_penjualanperbulan($periode)");



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




        $table = new easyTables($this->fpdf, "{5, 15, 8, 8}", 'border:1;font-size:9;');

        $table->rowStyle('font-style:B');
		$table->easyCell('NO', 'valign:M;align:C;');
		$table->easyCell('NAMA CLIENT', 'valign:M;align:C;');
		$table->easyCell('NAMA PAKET', 'valign:M;align:C;');
		$table->easyCell('TOTAL HARGA', 'valign:M;align:C;');
		$table->printRow();

        $i = 1;
        // echo "<pre>";
        // print_r($data_result);
        // echo "</pre>";
        // exit;
        foreach ($data_result as $value) {
            $table->easyCell($i++, 'valign:M;align:C;');
            $table->easyCell($value->nama_client, 'valign:M;align:L;');
            $table->easyCell($value->nama_paket, 'valign:M;align:L;');
            $table->easyCell('Rp ' . number_format($value->harga, 0, ',', '.'), 'valign:M;align:R;');            $table->printRow();
        }

        $table->rowStyle('font-style:B');
		$table->easyCell('TOTAL', 'colspan:3;valign:M;align:C;');
        $table->easyCell('Rp ' . number_format(array_sum(array_column($data_result, 'harga')), 0, ',', '.'), 'valign:M;align:R;');
		$table->printRow();

		$table->endTable(0);


        $this->fpdf->Output();
        exit;
    }
}
