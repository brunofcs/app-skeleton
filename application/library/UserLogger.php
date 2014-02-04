<?php

/**
 * These class log user actions on database
 *
 *
 * Autor: Bruno F. C. Silva
 */

class UserLogger {


	/*
	 * Function to write log in database
	 *
	 * @param $action
	 * @param $identification, Row identification, generaly PK data
	 * @param $complement, interesting complement to be logged
	 * @param $dataDiff, Diff data between old and new record
	 */
	public static function log($action, $identification = NULL, $complement = NULL, $dataDiff = NULL) {

		try {

			$user = Zend_Auth::getInstance()->getIdentity();
			$stmt = Zend_Registry::getInstance()->dbAdapter->prepare('EXECUTE PROCEDURE SPLCUSUARIO(\'' . Util::getRealIpAddr() . '\', ' . $action . ', \'' .
											  $user['CDUSNOME'] . '\', \'' . $identification . '\', \'' . $complement . '\', \'' . $dataDiff . '\')');
			$stmt->execute();

		} catch(Exception $e) {
			print $e->getMessage();
			throw new DSINWebAppsException(DSINWebAppsException::ERRO_GRAVACAO_LOG, 'Ex Code:' . $e->getCode());
		}
	}

}