<?php

class ModelsHelper {

    // Armazena uma instância da classe
    private static $instance;

    // Armazena as instancias dos models (singleton para os models)
    private $_modelsInstances = array();

    // Um construtor privado; previne a criação direta do objeto
    private function __construct() {}

    // O método singleton
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }


	public function __get($modelName) {

		// Verifica se já existe uma instância do modelo
		if(!isset($this->_modelsInstances[$modelName])) {

			if(!file_exists(APPLICATION_PATH . '/models/' . $modelName . '.php'))
				throw new DSINWebAppsException(DSINWebAppsException::ERRO_ARQUIVO_NAO_ENCONTRADO, 'Model ' . $modelName . '.');

           	require_once APPLICATION_PATH . '/models/' . $modelName . '.php';

            $this->_modelsInstances[$modelName] = new $modelName();

		}
        return $this->_modelsInstances[$modelName];
	}

}