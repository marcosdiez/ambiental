<?php
require_once("../libs/fpdf182/fpdf.php");
require_once("../libs/phpqrcode/qrlib.php");

$qrcode_file = tempnam(sys_get_temp_dir(), "qrcode_png_");

//create a QR Code and save it as a png image file named test.png
QRcode::png("coded number here", $qrcode_file);

//pdf stuff
$pdf = new FPDF('P' /* Portrait */ ,'mm','A4');
$pdf->SetFont('Courier','', 15);
$qr_code_size=100;  // mm
$pdf->SetTitle("QR Code Ambiental Floraliz");
$pdf->SetAuthor("Marcos Diez");
$pdf->AddPage();
$link = "http://www.uol.com.br";

$x_axis = ( $pdf->GetPageWidth() - $qr_code_size ) / 2;
$pdf->Image($qrcode_file, $x_axis, 0, $qr_code_size, $qr_code_size, "png", $link);

$msg = "blah blah [" . $pdf->GetPageHeight() . "] [" . $pdf->GetPageWidth() . "]";
$x_axis = ( $pdf->GetPageWidth() - $pdf->GetStringWidth($msg) ) / 2;
$pdf->Text($x_axis, $qr_code_size + 5  , $msg );
// $pdf->Text(10,10, "blah blah");// [" . $pdf->GetPageHeight() . "] [" . $pdf->GetPageWidth() . "]" );

$pdf->Output();

unlink($qrcode_file);