<?php

// Define the Application Path. These path should stay out of public directory
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));

// Set include path to whole application
set_include_path('.'.PATH_SEPARATOR.'../vendor'
	                .PATH_SEPARATOR.'../application/models/'
	                .PATH_SEPARATOR.'../application/library/'
	                .PATH_SEPARATOR.'../application/library/json'
	                .PATH_SEPARATOR.'../application/'
					.PATH_SEPARATOR.get_include_path()
					.PATH_SEPARATOR.APPLICATION_PATH . '/../vendor/');

// Load constants file
require_once(APPLICATION_PATH . '/config/constants.php');

// Load Zend Autoloader Class. With these class we don't need include almost files
require_once "Zend/Loader/Autoloader.php";

// Load Common Classes
require_once 'exceptions/DSINWebAppsException.php';
require_once 'SecurityHelper.php';
require_once 'JSONMessageFactory.php';
require_once 'ModelsHelper.php';

// Check Application environment to define debug configurations
if(getenv("APPLICATION_ENV") == DEVELOPMENT_MODE) {
	error_reporting(E_ALL|E_STRICT);
	ini_set('display_errors', 'On');
}

// Define Location and TimeZone
setlocale (LC_ALL, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');

// Define Zend Auto Loader
$autoloader = Zend_Loader_Autoloader::getInstance();

// Define and Configure Controller Dispatcher 
$controller = Zend_Controller_Front::getInstance();
$controller->throwExceptions(true);
$controller->registerPlugin(new Zend_Controller_Plugin_PutHandler()); // Add to add put head params on _request
$controller->registerPlugin(new Zend_Controller_Plugin_DeleteHandler()); // Add to add delete head params on _request
$controller->setControllerDirectory(
									array(
										'default' => APPLICATION_PATH . '/controllers',
										'rest'    => APPLICATION_PATH . '/controllersRest'
	                                )
	                               );

$controller->setParam('env', getenv("APPLICATION_ENV"));


// Define and Configure especial controller for restfull api
$restRoute = new Zend_Rest_Route($controller, array(), array('rest'));
$controller->getRouter()->addRoute('rest', $restRoute);


// Define and Confiruew Zend Layout
Zend_Layout::startMvc(APPLICATION_PATH . '/layouts');
$view = Zend_Layout::getMvcInstance()->getView();
$view->setEncoding('UTF-8');
$view->doctype('HTML5');


// Add Configuration to Zend registry (Application scope)
$registry = Zend_Registry::getInstance();
$registry->configuration = new Zend_Config_Ini(APPLICATION_PATH . '/config/app.ini', getenv("APPLICATION_ENV"));


// These configuration are not automatic. Here we have to configure database connections explicity
// -----------------------------------------------------------------------------------------------

// Define and Configure Database Connection
$dbAdapter = Zend_Db::factory($registry->configuration->database);
$registry->dbAdapter = $dbAdapter;

Zend_Db_Table_Abstract::setDefaultAdapter($registry->dbAdapter);


// // Conexão com o banco de imagem
// $dbImgAdapter 			= Zend_Db::factory($configuration->databaseImg);
// $registry->dbImgAdapter = $dbImgAdapter;

// -----------------------------------------------------------------------------------------------


// Load Zend Classes
Zend_Loader::loadClass('Zend_Debug');
Zend_Loader::loadClass('Zend_Auth');

try {

	try {

		// Dispatch req to correct Controller
		$controller->dispatch();

	// Catch Database connection exceptions
	} catch(ZendX_Db_Adapter_Firebird_Exception $zexfb) {

		// lanca excecao
		throw new DSINWebAppsException(DSINWebAppsException::ERRO_BANCO_DADOS_DESCONECTADO);
	}
} catch(DSINWebAppsException $dwaex) {

	// Adiciona mensagem de excecao
    JSONMessage::getInstance()->add(JSONMessageFactory::factoryException($dwaex->getMessage(), true));

    // send response to user
    echo JSONMessage::getInstance();
}


// Unset Variables
unset($autoloader, $controlador, $view, $configuration, $registry);