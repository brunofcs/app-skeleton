<?php
require_once "JSONBaseMessage.php";

/* Classe para retorno de mensagens tipo JSON */
class JSONMessageRedir extends JSONBaseMessage {

	/**
	 * Campos utilziados para redirecionamento e mostrar páginas html
	 */
	public $url;
}