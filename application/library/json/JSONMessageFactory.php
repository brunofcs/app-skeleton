<?php
require_once 'JSONMessage.php';
require_once 'JSONType.php';
require_once 'JSONMessageRedir.php';
require_once 'JSONMessageFormValidation.php';
require_once 'JSONMessageException.php';
require_once 'JSONMessageForm.php';
require_once 'JSONMessageClearForm.php';
require_once 'JSONMessageGrid.php';


/* 
 * Classe utilitária para criar mensagens de resposta JSON.
 */
class JSONMessageFactory {

	/*
	 * Cria mensagem JSON de resposta para redirecionamento
	 */
	public static function factoryRedir($url) {
		// Cria o objeto JSON
		$jsonM          = new JSONMessageRedir();
		// Atribui o tipo da mensagem
		$jsonM->type    = JSONType::REDIRECT;
		// Atribui a URL para redirecionamento
		$jsonM->url     = $url;

		// Retorna o objeto da mensagem
		return $jsonM;
	}

	/*
	 * Cria mensagem JSON de resposta validacao de formulario
	 */
	public static function factoryFormValidation($errors) {
		// Cria o objeto JSON
		$jsonM             = new JSONMessageFormValidation();
		// Atribui o tipo da mensagem
		$jsonM->type       = JSONType::FORMVALIDATION;
		// Atribui a URL para redirecionamento
		$jsonM->errors     = $errors;
		// Atribui o campo de erro apra foco
		$jsonM->fieldFocus = $errors[0]['field'];

		// Retorna o objeto da mensagem
		return $jsonM;
	}

	/*
	 * Cria mensagem JSON de Excecao
	 */
	public static function factoryException($message, $showAlert = false, $fieldFocus='') {
		// Cria o objeto JSON
		$jsonM             = new JSONMessageException();
		// Atribui o tipo da mensagem
		$jsonM->type       = JSONType::EXCEPTION;
		// Mensagem da Excecao
		$jsonM->message    = $message;
		// Mensagem da Excecao
		$jsonM->showAlert  = $showAlert;
		// Campo que deve receber o foco
		$jsonM->fieldFocus = $fieldFocus;

		// Retorna o objeto da mensagem
		return $jsonM;
	}

	/*
	 * Cria mensagem JSON de resposta para preenchimento de formulario
	 */
	public static function factoryForm($formData, $fieldFocus='', $message = '', $showAlert = false) {
		// Cria o objeto JSON
		$jsonM          = new JSONMessageForm();
		// Atribui o tipo da mensagem
		$jsonM->type    = JSONType::FORM;
		
		// Verifica se existe mensagem
		if(!empty($message))
			$jsonM->message = $message;
		
		// Atribui os dados do formulario
		$jsonM->content    = $formData;
		
		// Campo para Foco
		$jsonM->fieldFocus = $fieldFocus;

		// Adiciona informacao de laerta
		$jsonM->showAlert = $showAlert;

		// Retorna o objeto da mensagem
		return $jsonM;
	}

	/*
	 * Cria mensagem JSON de resposta para preenchimento de formulario
	 */
	public static function factoryClearForm($fieldFocus='', $message = '', $showAlert = false) {
		// Cria o objeto JSON
		$jsonM          = new JSONMessageClearForm();
		// Atribui o tipo da mensagem
		$jsonM->type    = JSONType::CLEARFORM;
		
		// Verifica se existe mensagem
		if(!empty($message))
			$jsonM->message = $message;
		
		// Campo para Foco
		$jsonM->fieldFocus = $fieldFocus;

		// Adiciona informacao de laerta
		$jsonM->showAlert = $showAlert;

		// Retorna o objeto da mensagem
		return $jsonM;
	}

	/*
	 * Cria mensagem JSON de resposta de Grid
	 *
	 * Esta Mensagem não deve ser adicionada ao JSON Message
	 */
	public static function factoryGrid($data=array(), $searchRecCount, $tableRecCount) {

		// Cria o objeto JSON
		$jsonM          = new JSONMessageGrid();
		
		// Atribui os registros do Grid
		$jsonM->records = $data;
		
		// Campo para Foco
		$jsonM->queryRecordCount = $searchRecCount;

		// Adiciona informacao de laerta
		$jsonM->totalRecordCount = $tableRecCount;

		// Retorna o objeto da mensagem
		return $jsonM;
	}

}