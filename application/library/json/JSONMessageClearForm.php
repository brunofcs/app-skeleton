<?php
require_once "JSONBaseMessage.php";

/* Classe para retorno de mensagens tipo JSON */
class JSONMessageClearForm extends JSONBaseMessage {

	/**
	 * Mensagem que deve ser apresentada apos o preenchimento do formulario
	 */
	public $message;

	/**
	 * Show alert or put message on container
	 */
	public $showAlert;

}