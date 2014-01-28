<?php

/**
 * Classe JSONMessage Armazena todas as mensagens que devem ser respondidas para o
 * cliente (FrontEnd)
 *
 * Esta classe implementa o design pattern Singleton
 *
 */
class JSONMessage {

	// Armazena a unica instancia do objeto
	protected static $instance = null;

	private function __construct() {}

	private function __clone()     {}

	/** 
	 * Metodo utilizado para recuperar uma instancia da classe JSONMessage
	 */
	public static function getInstance() {
		if(!isset(static::$instance)) {
			static::$instance = new static;
		}
		return static::$instance;
	}

	/** 
	 *  Local de armazenamento de todas as mensagens que devem ser devolvidas
	 *  para o cliente
	 */
	private $messages = array();


	/**
	 * Adiciona mensagem
	 *
	 * Este metodo retorna a instancia da propria classe para que possa ser utilizada em
	 * cascata ou mesmo em conjunto com o (print ou echo)
	 * 
	 */
	public function add($message) {
		$this->message[] = $message;

		return static::$instance;
	}

	/** 
	 * metodo chamado quando a classe é utilizada como argumento de print ou echo
	 */
	public function __toString() {
		return Zend_Json::encode($this);
	}

}