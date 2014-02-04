<?php

class DSINWebAppsException extends Exception {

	const ERRO_DESCONHECIDO             = 1;
	const ERRO_BANCO_DADOS_DESCONECTADO = 2;
	const ERRO_ARQUIVO_NAO_ENCONTRADO   = 3;
	const ERRO_REGISTRO_NAO_ENCONTRADO  = 4;
	const ERRO_GRAVACAO_LOG			 	= 5;

	/*
	 * Construtor definido para chamada do construtor da classe extendida
	 */
	public function __construct($msg, $detail='', $codigo=0) {

		// construtor de um unico parametro
		if(is_int($msg)) {

			$mensagem = "";

			switch($msg) {

				case self::ERRO_BANCO_DADOS_DESCONECTADO:
					$mensagem = 'Atenção. Ocorreram erros durante requisiçao. Entre em contato com o suporte e informe o codigo: ' . str_pad($msg, 4, "0", STR_PAD_LEFT);
					break;

				case self::ERRO_ARQUIVO_NAO_ENCONTRADO:
					$mensagem = 'Atenção. O Arquivo solicitado não foi encontrado.';
					break;

				case self::ERRO_REGISTRO_NAO_ENCONTRADO:
					$mensagem = 'Atenção. O registro solicitado não foi encontrado.';
					break;

				default:
					$mensagem = "Ocorreram erros no processamento da Informacao. Entre em contato com o administrador do Sistema.";
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
