<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Codedge\Fpdf\Fpdf\Fpdf;
use App\Libraries\easyTables;
use App\Libraries\exFPDF;

class PDFController extends Controller
{
    protected $fpdf;
 
    public function __construct()
    {
        $this->fpdf = new exFPDF;
    }

    public function index() 
    {
    	$this->fpdf->SetFont('Arial', 'B', 15);
        $this->fpdf->AddPage("L", ['100', '100']);
        $this->fpdf->Text(10, 10, "Hello World!");

        $table8 = new easyTables($this->fpdf, 4, 'border:1;font-size:7;  font-style:R;');
		$table8->easyCell('1', 'Valign:C;align:C;');
		$table8->easyCell('2', 'Valign:C;align:C;');
		$table8->easyCell('3', 'Valign:C;align:C;');
		$table8->easyCell('4', 'Valign:C;align:C;');
		$table8->printRow();
		$table8->endTable(0);
         
        $this->fpdf->Output();

        exit;
    }
}
