<?php
require_once "JSONBaseMessage.php";

/* Classe para retorno de mensagens tipo JSON */
class JSONMessageFormValidation extends JSONBaseMessage {

	/**
	 * Campos contendo os erros encontrados no formulário
	 */
	public $errors;

}