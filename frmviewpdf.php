<?php

include("../../configuration.php");
// Tidak perlu OPen Connection Cos Class pdf use New Open
//include("../../connection.php");
include("../../endec.php");

//Class For Pdf
require('mysql_report.php');

//Cek Get Data
if(isset($_POST['nmSQL'])){
  $txtSQL = $_POST['nmSQL'];
}else{
  $txtSQL = "";
}

$pdf = new PDF('L','pt','A4');
$pdf->SetFont('Arial','',12);
$pdf->connect($hostmysql,$username,$password,$database);
$attr = array('titleFontSize'=>18, 'titleText'=>'Laporan User');
$pdf->mysql_report($txtSQL,false,$attr);
$pdf->Output();
?>
