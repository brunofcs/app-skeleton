<?php

class ImpressaoException extends Exception {

	const ERRO_NAO_SUPORTADO = 1;

	/*
	 * Construtor definido para chamada do construtor da classe extendida
	 */
	public function __construct($msg, $detail='', $codigo=0) {

		// construtor de um unico parametro
		if(is_int($msg)) {

			$mensagem = "";

			switch($msg) {

				case self::ERRO_NAO_SUPORTADO:
					$mensagem = 'Atenção. O Formato solicitado não é suportado para este relatório';
					break;

				default:
					$mensagem = "Ocorreram erros no processamento da Impressão. Entre em contato com o administrador do Sistema.";
					break;
			}

			$mensagem .= ' ' . $detail;

			// Chama o construtor da super classe
			parent::__construct($mensagem, $msg);

		} else {

			// Chama o construtor de Exception
			parent::__construct((string)$msg, (int)$codigo);

		}
	}
}
