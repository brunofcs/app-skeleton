<?php
require_once 'exceptions/ImpressaoException.php';


interface AppPrint {

	public function writePDF();
	public function writeHTML();
	public function writeExcel();
}