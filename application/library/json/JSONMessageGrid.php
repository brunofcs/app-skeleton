<?php
require_once "JSONBaseMessage.php";

/* Classe para retorno de mensagens tipo JSON */

/*
 * Esta Resposta JSON não deve ser adicionado ao JSONMessage 
 */
class JSONMessageGrid {

	/**
	 * Campo contendo os registros do grid
	 */
	public $records;

	/*
	 * Quantidade de registros retornados pela consulta
	*/
	public $queryRecordCount;

	/*
	 * Total de Registros existentes
	 */
	public $totalRecordCount;
}