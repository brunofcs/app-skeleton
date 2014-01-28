<?php
require_once APPLICATION_PATH . '/adapter/CustomAuthAdapter.php';

require_once 'DefaultViewController.php';


class AutenticacaoController extends DefaultViewController
{

    public $_camposForm = array('user'=>'', 'password'=>'');

    public function preDispatch() {

        // Executa o Método de preparação e segurança dos dados enviados pelo usuário
        // parent::preDispatch();

    }

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

    }

    public function indexAction() {

        echo Zend_Json::encode(array(
            array('id' => 1003, 'title' => 'Article 1'),
            array('id' => 1004, 'title' => 'Article 2')
        ));

         exit;

         // $this->getResponse()
         //    ->appendBody("From indexAction() returning all articles");
    }

    public function getAction()
    {
        $this->getResponse()
             ->appendBody("From getAction() returning the requested article");
    }
    
    /**
     * Metodo Post da API rest para executar o Login
     * 
     * Exceptionamente o POST está sendo utilizado para fins que não são de
     * criação de registro.
     *
     * Isto porque a deveria ser utilizado o metodo get para recuperacao do usuário
     * porém este método expõe a senha preenchida no formulario.
     *
     */
    public function postAction()
    {

        try {

            // Cria uma Instância do Autenticador
            $authAdapter = new CustomAuthAdapter($this->_camposForm['user'], $this->_camposForm['password']);
        
            // Executa a Autenticacao do Usuário e persiste o mesmo em sessao 
            // caso o usuario e senha estejam corretos
            @Zend_Auth::getInstance()->authenticate($authAdapter);
        
            // Envia uma mensagem de Redirecionamento para a Interface
            echo JSONMessage::getInstance()->add(JSONMessageFactory::factoryRedir(APP_URL.'webApps'));    

        } catch(Exception $ex) {

            // Limpa a autenticacao da sessao corrente
            Zend_Auth::getInstance()->clearIdentity();

            echo Zend_Json::encode($this->_camposForm);

        }

        exit;
    }
}