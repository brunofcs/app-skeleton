<?php
/**
 * Classe utilizada para segurança da aplicação
 *
 */
class SecurityHelper {

	/**
	 * Verifica se o usuario efetuou login
	 *
	 *	return: True caso o login tenha sido efetuado e false caso contrário
	 *
	 */
	public static function verifyAuth() {
		// Recupera  a instancia de autenticação
		$auth = Zend_Auth::getInstance();

		// Verifica se existe usuário autenticado
		if(!$auth->hasIdentity()) {
			// Não possui login
			return false;
		}

		// Possui login
		return true;
	}

	/**
	 * Extrai caracteres invalidos de dados enviados
	 *
	 *	return: Array contendo dados seguros para processamento
	 *
	 */
	public static function secureEnvData($data) {

		// Filtro de Remoção de Tags
		Zend_Loader::loadClass('Zend_Filter_StripTags');
		$f = new Zend_Filter_StripTags();

		// Percorre os dados enviados
		foreach($data as $k => $v) {
			if(!is_array($v)) {
				// Remove os caracteres indesejados
				$data[$k] = str_replace("'", "", str_replace('"', "", $f->filter($v)));
			} else {
				foreach ($v as $k2 => $v2) {
					$v[$k2] = str_replace("'", "", str_replace('"', "", $f->filter($v2)));
				}
				$data[$k] = $v;
			}
		}

		return $data;
	}

	/**
	 *  Prepara as informacoes para serem processadas
	 *  @Param array campos do formulario
	 *  @Param array dados do formulario
	 *	@return: Array contendo dados seguros para processamento
	 *
	 */
	public static function preparaDadosFormulario($camposForm, $dadosForm) {

		//percorre os campos do formulario
		foreach($camposForm as $k => $v) {
			$v = @$dadosForm[$k];

			// Caso o campo não seja um array
			if(!is_array($v)) {

				if (empty($v) && $v != '0') {
					$v = NULL;
				} else {

					// Converte letras com acento em Maiúsculo
					/*$v = utf8_decode($v);
					$v = strtoupper($v);
					$v = utf8_encode($v);*/
					$v = mb_convert_case($v, MB_CASE_UPPER, "UTF-8");

				}
				$camposForm[$k] = $v != NULL ? str_replace("'", "", str_replace('"', "", $v)) : $v;

			// Caso o campo não seja um array (EX: array de checkbox)
			} else {

				// Percorre o campo array
				foreach($v as $kCheck => $vCheck) {
					// Verifica se deve ser setado nulo para o campo
					if (empty($vCheck) && $vCheck != '0') {
						$vCheck = NULL;

					// Existe valor então o campo deve ser convertiddo para maiúsculo
					} else {
						// Converte letras com acento em Maiúsculo
						/*$v = utf8_decode($v);
						$v = strtoupper($v);
						$v = utf8_encode($v);*/
						$vCheck = mb_convert_case($vCheck, MB_CASE_UPPER, "UTF-8");
					}
					// Atualiza o Array de Checkboxes
					$v[$kCheck] = $vCheck != NULL ? str_replace("'", "", str_replace('"', "", $vCheck)) : $vCheck;
				}
				// Atualiza no array de campos
				$camposForm[$k] = $v;
			}
		}
		return $camposForm;
	}
}