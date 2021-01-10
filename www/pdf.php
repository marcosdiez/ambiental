<?php
require_once("../libs/fpdf182/fpdf.php");
require_once("../libs/phpqrcode/qrlib.php");
require_once(__DIR__ . "/common.php");
require __DIR__ . '/../libs/vendor/autoload.php';

function pdf_init(){
    global $font_size;
    $pdf = new FPDF('P' /* Portrait */ ,'mm','A4');
    $pdf->SetFont('Courier','', $font_size);
    $pdf->SetTitle("QR Code Ambiental Floraliz");
    $pdf->SetAuthor("Marcos Diez");
    $pdf->SetAuthor("pdf.php");
    return $pdf;
}

function add_page($pdf, $msg){
    global $qr_code_size;
    global $space_between_qr_and_link;

    $qrcode_file = tempnam(sys_get_temp_dir(), "qrcode_png_");
    QRcode::png($msg, $qrcode_file);

    $pdf->AddPage();
    $x_axis = ( $pdf->GetPageWidth() - $qr_code_size ) / 2;
    $pdf->Image($qrcode_file, $x_axis, 0, $qr_code_size, $qr_code_size, "png");

    $x_axis = ( $pdf->GetPageWidth() - $pdf->GetStringWidth($msg) ) / 2;
    $pdf->Text($x_axis, $qr_code_size + $space_between_qr_and_link, $msg );
    unlink($qrcode_file);
}

function create_pdf($project, $id){
    global $base_url;
    global $project_has_no_data;

    $xlsx = get_xlsx($project, $id);
    $pdf = pdf_init();

    $first = true;
    $counter = 0;
    foreach( $xlsx->rows() as $row ) {
        if($first){
            $first=false; // we don't care about the headers
            $headers = title_to_dict($row);
            continue;
        }else{
            $msg = $base_url . "?q=" . $project . "/" . $row[$headers["id"]];
            add_page($pdf, $msg);
            $counter++;
            // print($msg  . "<br>\n");
        }
    }
    if($counter == 0){
        header('Content-Type: text/html; charset=utf-8');
        die($project_has_no_data);
    }
    $pdf->Output();
}

if(isset($_GET["q"])){
    $q = explode("/", $_GET["q"]);
    create_pdf($q[0], $q[1]);
    exit();
}
create_pdf(@$_GET["projeto"], @$_GET["id"]);

// $qrcode_file = tempnam(sys_get_temp_dir(), "qrcode_png_");

// //create a QR Code and save it as a png image file named test.png
// QRcode::png("coded number here", $qrcode_file);

// //pdf stuff
// $pdf = new FPDF('P' /* Portrait */ ,'mm','A4');
// $pdf->SetFont('Courier','', 15);
// $qr_code_size=100;  // mm
//
// $pdf->SetAuthor("Marcos Diez");
// $pdf->AddPage();
// $link = "http://www.uol.com.br";

// $x_axis = ( $pdf->GetPageWidth() - $qr_code_size ) / 2;
// $pdf->Image($qrcode_file, $x_axis, 0, $qr_code_size, $qr_code_size, "png", $link);

// $msg = "blah blah [" . $pdf->GetPageHeight() . "] [" . $pdf->GetPageWidth() . "]";
// $x_axis = ( $pdf->GetPageWidth() - $pdf->GetStringWidth($msg) ) / 2;
//
// // $pdf->Text(10,10, "blah blah");// [" . $pdf->GetPageHeight() . "] [" . $pdf->GetPageWidth() . "]" );

// $pdf->Output();

// unlink($qrcode_file);