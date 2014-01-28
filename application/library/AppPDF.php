<?php

// Carrega a Biblioteca TCPDF
require_once('tcpdf/tcpdf.php');

class AppPDF extends TCPDF {

    //Page header
    public function Header() {

        // Set font
        $this->SetFont('helvetica', 'B', 20);

        $this->ln(10);

        $this->setX(30);

        $y      = $this->getY();
        $margin = $this->getMargins();

        // Title
        $this->Cell(0, 25, 'Create Header of App Here', 0, false, 'C', 0, '', 0, false, 'C', 'M');
        
        $image_file = APPLICATION_PATH . '/../public/img/skeletonLogo.jpg';
        $this->Image($image_file, 10, 5, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }


    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages() . ' - ' . date('d/m/Y h:i:s'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}