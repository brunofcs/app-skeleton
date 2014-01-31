<?php
require_once 'DefaultViewController.php';

require_once 'FormValidatorHelper.php';

require_once 'dataModules/OpcImpressaoTesteDataModule.php';


/**
 * Controller de Autenticação
 *
 * OBS: Toda classe não rest deve extender DefaultViewController
 */
class OpcImpressaoTesteController extends DefaultViewController {

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
        // Unico local (aqui e nas chamadas para) do sistema que é permitido a utilizacao go getParam e apenas para esta funcao
        $this->view->menuURL = $this->_dataModule->camposForm['controller'] = $this->_request->getParam('controller');
    }
    
    /**
     * Inicializacao do Controller
     * 
     * Sempre deve incializar o data module do controller
     * 
     */
    public function init() {

        // Instancia o ModuleData da Rotina
        $this->_dataModule = new OpcImpressaoTesteDataModule();

    }

    /**
     * 
     *  Mostra a view principal do cadastro teste
     *
     */
    public function indexAction() {

        $this->_dataModule->atribuiPermissoesBotoes(FORM_PRINT);

        // Atribui a permissão para o botão antecipadamente, visto que
        // A página não realiza buscas JSON. Desta forma, para evitar uma postagem,
        // a disponibilizacao da permissao do botão é antecipada
        $this->view->btnImprimir = $this->_dataModule->camposForm['btnImprimir'];
    }

    public function imprimeAction() {

        try {

            $qryRegistros = $this->_dataModule->qryBuscaRegistros();

            switch($this->_dataModule->camposForm['formato']) {

                case 1:
                    // Desabilita a View já que a saida é no formato pdf
                    $this->_helper->viewRenderer->setNoRender(true);
                    $this->_helper->layout->disableLayout();

                    require_once "print/ImpressaoTesteRelatorio.php";

                    break;

                case 2:
                    
                    // Disponibiliza os dados buscados para a View
                    $this->view->qryRegistros = $qryRegistros;

                    break;

                case 3:
                    // die('Formato não disponível');
                    break;

            }

        } catch(Exception $e) {

            echo $e->getMessage();
        }

    }

}