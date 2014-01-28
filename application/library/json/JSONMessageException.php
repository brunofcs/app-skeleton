<?php
require_once "JSONBaseMessage.php";

/* Classe para retorno de mensagens tipo JSON */
class JSONMessageException extends JSONBaseMessage {

	/**
	 * Campo com o conteúdo da mensagem de excecao
	 */
	public $message;

	/**
	 * Campo boolean indicando se deve ser mostrado um alerta / popup da mensagem
	 */
	public $showAlert;
}