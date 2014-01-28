<?php
// Recupera o Usuario
$usuario = Zend_Auth::getInstance()->getIdentity();

require_once('AppPDF.php');

// create new PDF document
$pdf = new AppPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(APP_OWNER);           // Sempre usar esta constante
$pdf->SetAuthor($usuario['CDUSNOME']); // Sempre Passar o Nome do Usuário que Gerou o Relatório 
$pdf->SetTitle('Titulo do Relatório'); // Sempre informar com o titulo do relatorio

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
// $pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(1, PDF_MARGIN_TOP, 1);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();


// Codigo de impressao do relatorio abaixo

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<h1>Welcome DSINWebAppSkeletonto  !</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT</p>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------
$pdf->ln(10);
$pdf->SetFont('dejavusans', '', 6, '', true);

$tableInfo = $qryRegistros->getTable()->info();

$colCount = count($tableInfo['cols']);
$colW = ($pdf->getPageWidth()/$colCount);

$colCount--;

$pdf->setFillColor(175);
for($i = 0; $i < count($tableInfo['cols']); $i++) {
	$pdf->Cell($colW, 10, $tableInfo['cols'][$i], 1, false, 'C', 1, '', 0, false, 'C', 'M');
}

$pdf->ln();

$pdf->setFillColor(200);
$fill = false;
foreach($qryRegistros as $row) {
	$arr = $row->toArray();
	foreach($arr as $k => $v) {
		$pdf->Cell($colW, 10, $v, 1, false, 'C', $fill, '', 0, false, 'C', 'M');
	}
	$fill = !$fill;
	$pdf->ln();
	
} 

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');