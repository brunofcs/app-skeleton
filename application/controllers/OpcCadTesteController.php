<?php
require_once 'DefaultViewController.php';

require_once 'FormValidatorHelper.php';

require_once 'dataModules/OpccadTesteDataModule.php';

/**
 * Controller de Autenticação
 *
 * OBS: Toda classe não rest deve extender DefaultViewController
 */
class OpcCadTesteController extends DefaultViewController {

    // Data Model do Controller
    private $_dataModule = null;


    /**
     *
     * Executado antes de dispachar a requisicao para as actions
     *
     * Executa as seguintes atividades:
     *    1 - Verifica se o usuario esta logado
     *    2 - Remove informacoes inseguras dos dados enviados
     *    3 - Prepara os dados enviados para utilizacao
     *    4 - Disponibiliza o nome do controller para view
     *
     */
    public function preDispatch() {

        // Executa o pre dispatch da classe DefaultViewController para verificacoes padrao de todos os controllers
        parent::preDispatch();

        // Extrai caracteres invalidos de dados enviados
        $this->_request->setParams(SecurityHelper::secureEnvData($this->_request->getParams()));

        // Extrai caracteres invalidos de dados enviados
        $this->_dataModule->camposForm = SecurityHelper::preparaDadosFormulario($this->_dataModule->camposForm, $this->_request->getParams());

        // disponibiliza o nome do controller para view. Deve ser executado apenas após da preparacao dos dados
        // Unico local do sistema que é permitido a utilizacao go getParam e apenas para esta funcao
        $this->view->menuURL = $this->_request->getParam('controller');
    }
    
    /**
     * Inicializacao do Controller
     * 
     * Sempre deve incializar o data module do controller
     * 
     */
    public function init() {

        // Instancia o ModuleData da Rotina
        $this->_dataModule = new OpcCadTesteDataModule();

    }

    /**
     * 
     *  Mostra a view principal do cadastro teste
     *
     */
    public function indexAction() {}

    /**
     * Proximo Codigo
     *
     *  Recupera o proximo codigo de cadastro para a interface
     *
     */
    public function proximocodigoAction() {

        // Desabilita a view. Estes comandos são utilizados sempre que a resposta for JSON
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        try {

            // Busca o proximo codigo de cadastro e o atribui no formulario
            $this->_dataModule->camposForm['codigo'] = $this->_dataModule->qryBuscaProximoCodigo();

            // Recupera as permissoes dos botoes
            $this->_dataModule->atribuiPermissoesBotoes(FORM_ADD);

            // Adiciona a resposta de preenchimento de formulario
            JSONMessage::getInstance()->add(JSONMessageFactory::factoryForm($this->_dataModule->camposForm, 'descricao'));

        } catch(DSINWebAppsException $dwaex) {
            
            // Adiciona mensagem de excecao
            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException($dwaex->getMessage()));

        }

        echo JSONMessage::getInstance();
        exit;

    }

    /**
     * Tela de Procura para o cadastro de teste
     *
     */
    public function procuraAction() {}
}