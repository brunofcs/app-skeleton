<?php

/**
 * CustomAuthAdapter e o responsavel por realizar a autenticacao e autorizacao dos usuarios
 *
 * O método authenticate deve ser implementado seguindo a necessidade da aplicacao
 * Ex: por meio de arquivo texto, autenticacao http base ou banco de dados.
 * 
 */

class CustomAuthAdapter implements Zend_Auth_Adapter_Interface {

	const FAILURE_MESSAGE_EXPIRED  = "Usuário expirado";

	const FAILURE_MESSAGE_BLOCKED  = "Usuário bloqueado.";

	const FAILURE_MESSAGE_CANCELED = "Usuário cancelado.";

	const FAILURE_MESSAGE_ATTEMPTS = "Número máximo de tentativas atingido.";

	protected $usuario;
	protected $senha;

	/**
	* Configura nome de usuário e senha para autenticação
	*
	* @return void
	*/
	public function __construct($usuario, $senha) {
		$this->usuario = $usuario;
		$this->senha   = $senha;
	}

	/**
	* Executa uma tentativa de autenticação
	*
	* @throws Zend_Auth_Adapter_Exception Se a autenticação não pode ser executada
	* @return Zend_Auth_Result
	*/
	public function authenticate()
	{

		if($this->usuario == 'ADMIN@ADMIN.COM.BR' && $this->senha == 'ADMIN') {


			// Verificar o numero de tentativas

			// Verificar se o usuario está expirado e retornar erro conforme ex abaixo
			//return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, self::FAILURE_MESSAGE_BLOCKED);

			// Verificar se o usuario está bloqueado

			// Verificar se foi cancelado

			$usuario = array('CDUSNOME' => $this->usuario, 'CDUSGRUPO'=>'ADMIN');

			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $usuario);

		} else
			return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null);
			//throw new Zend_Auth_Adapter_Exception('Usuário Inválido');
	}
}
