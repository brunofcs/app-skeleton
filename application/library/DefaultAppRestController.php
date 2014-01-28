<?php
require_once 'json/JSONMessage.php';
require_once 'json/JSONMessageFactory.php';

class DefaultAppRestController extends Zend_Rest_Controller {

    public function preDispatch() {

        // Verifica se o login foi efetuado
        SecurityHelper::verifyAuth($this) == false ? die('User not logged in.') : '';

        // Extrai caracteres invalidos de dados enviados
        $this->_request->setParams(SecurityHelper::secureEnvData($this->_request->getParams()));

    }

    public function init()
    {
        // define que para este tipo de controller não existira visualizacao
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

    }

    // Action chamada por meio de uma solicitacao GET
	public function indexAction() {}
    public function getAction()   {}

    // Action chamada por meio de uma solicitacao do tipo POST
    public function postAction()  {}

    // Action chamada por meio de uma solicitacao do tipo PUT
    public function putAction()   {}

    // Action chamada por meio de uma solicitacao do tipo DELETE
    public function deleteAction(){}

    // Action chamada por meio de uma solicitacao do tipo HEAD
    public function headAction()  {}
}