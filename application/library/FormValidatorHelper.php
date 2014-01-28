<?php

/**
 * Classe contendo dados e metodos para validação de formularios 
 *
 *
 * Autor: Bruno F. C. Silva
 */

class FormValidatorHelper {


	const ERROR_CODE_UNKNOWN                = 0;

	const ERROR_CODE_EMPTY_FIELD            = 1;

	const ERROR_CODE_INCORRECT_EMAIL_FORMAT = 2;

	const ERROR_CODE_INCORRECT_DATA_TYPE    = 3;



	private static $_errorMessages = array(

								self::ERROR_CODE_UNKNOWN                 => "Desconhecido",
								self::ERROR_CODE_EMPTY_FIELD             => "Obrigatório",
								self::ERROR_CODE_INCORRECT_EMAIL_FORMAT  => "Formato Incorreto",
								self::ERROR_CODE_INCORRECT_DATA_TYPE     => "Tido de Dado Incorreto",
							 );

	public static function addError($field, $errorCode) {

		if(!isset(self::$_errorMessages[$errorCode]))
			$errorCode = self::ERROR_CODE_UNKNOWN;

		return array('field'=>$field, 'msg'=> self::$_errorMessages[$errorCode], 'errCode'=>$errorCode);

	}



	/**
	* Validate an email address.
	* Provide email address (raw input)
	* Returns true if the email address has the email 
	* address format and the domain exists.
	*/
	public static function validateEmail($email, $checkDNS = false) {
	   $isValid = true;

	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex) {
	    
	      $isValid = false;

	   } else {

	      $domain    = substr($email, $atIndex+1);
	      $local     = substr($email, 0, $atIndex);
	      $localLen  = strlen($local);
	      $domainLen = strlen($domain);

	      if ($localLen < 1 || $localLen > 64) {
	         
	         // local part length exceeded
	         $isValid = false;

	      } else if ($domainLen < 1 || $domainLen > 255) {
	         
	         // domain part length exceeded
	         $isValid = false;

	      } else if ($local[0] == '.' || $local[$localLen-1] == '.') {

	         // local part starts or ends with '.'
	         $isValid = false;

	      } else if (preg_match('/\\.\\./', $local)) {

	         // local part has two consecutive dots
	         $isValid = false;

	      } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {

	         // character not valid in domain part
	         $isValid = false;

	      } else if (preg_match('/\\.\\./', $domain)) {
	         
	         // domain part has two consecutive dots
	         $isValid = false;

	      } else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {

	         // character not valid in local part unless 
	         // local part is quoted
	         if (!preg_match('/^"(\\\\"|[^"])+"$/',
	             str_replace("\\\\","",$local)))
	         {
	            $isValid = false;
	         }
	      }

	      if ($isValid && $checkDNS && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
	         // domain not found in DNS
	         $isValid = false;
	      }

	   }

	   return $isValid;
	}
}