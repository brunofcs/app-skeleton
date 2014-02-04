<?php
/**
 * IndexController is the default controller for this application
 * 
 * Notice that we do not have to require 'Zend/Controller/Action.php', this
 * is because our application is using "autoloading" in the bootstrap.
 *
 * @see http://framework.zend.com/manual/en/zend.loader.html#zend.loader.load.autoload
 */
class DefaultViewController extends Zend_Controller_Action {

    public function preDispatch() {

        // Verifica se o login foi efetuado
        SecurityHelper::verifyAuth($this) == false ? $this->_redirect("/") : '';

        // Extrai caracteres invalidos de dados enviados
        $this->_request->setParams(SecurityHelper::secureEnvData($this->_request->getParams()));
    }
}