<?php
require_once 'DefaultAppRestController.php';

require_once 'FormValidatorHelper.php';

require_once 'dataModules/OpccadTesteDataModule.php';


class Rest_OpcCadTesteController extends DefaultAppRestController {

    private $_dataModule = null;

    /**
     *
     * Executado antes de dispachar a requisicao para as actions
     *
     * Executa as seguintes atividades:
     *    1 - Verifica se o usuario esta logado
     *    2 - Remove informacoes inseguras dos dados enviados
     *    3 - Prepara os dados enviados para utilizacao
     */
    public function preDispatch() {

        parent::preDispatch();

        /* @var $ajaxContext Zend_Controller_Action_Helper_AjaxContext */
        // $ajaxContext = $this->_helper->getHelper('AjaxContext')->addActionContext('index', 'json'); 
        // $ajaxContext->addActionContext('get', 'html');
        // $ajaxContext->addActionContext('post', 'json');
        // $ajaxContext->addActionContext('put', 'json');
        // $ajaxContext->addActionContext('delete', 'json');
        // $ajaxContext->initContext();
        $this->_helper->ajaxContext->initContext();

        // Extrai caracteres invalidos de dados enviados
        $this->_request->setParams(SecurityHelper::secureEnvData($this->_request->getParams()));

        // Extrai caracteres invalidos de dados enviados
        $this->_dataModule->camposForm = SecurityHelper::preparaDadosFormulario($this->_dataModule->camposForm, $this->_request->getParams());

    }

    /**
     *
     * Inicialização do Controller
     *
     * Este metodo é uma extensao do contrutor da classe pai
     */
    public function init() {

        // Desabilita a View para qualquer chamada deste controller
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        // Instancia o data Module da Rotina
        $this->_dataModule = new OpcCadTesteDataModule();
    }

    /**
     * 
     * No Padrão rest este action é chamado por requisicoes GET sem a identificacao do elemento,
     * ou seja, é utilizado para carregar listagens de registroa ao qual este controller corresponde.
     *
     * Ex de utilização comum: Grid das telas de procura, mas pode ser utilizado para qualquer lista de dados
     *
     */
    public function indexAction() {
         
        try {

            // Prepara o campo de Filtro
            // -------------------------
            $campoBusca = "CDCDTDESCRICAO"; // Campo de Filtro Padrao

            // Verifica se foi selecionado outro campo de filtro
            if(isset($this->_dataModule->camposForm['queries']['campoBusca']))
                $campoBusca = $this->_dataModule->camposForm['queries']['campoBusca'] == 'OBSERVACAO' ? 'CDCDTOBSERVACAO' : 'CDCDTDESCRICAO';


            // Prepara o Conteudo para Filtro
            // -------------------------------
            $valorBusca = (isset($this->_dataModule->camposForm['queries']['busca']) ? $this->_dataModule->camposForm['queries']['busca'] : '');

            // Prepara a ordenação
            // -------------------------------
            $ordenacao = array('CDCDTDESCRICAO ASC');

            // Verifica se a ordenacao foi enviada na requisicao
            if(count($this->_dataModule->camposForm['sorts']) > 0) {

                // Prepara a ordenacao solicitada
                $novaOrdenacao = array();

                foreach($this->_dataModule->camposForm['sorts'] as $field => $type) {

                    // Adiciona a ordenacao do campo ao array
                    // Tipo 1  = Asc
                    // Tipo -1 = Desc
                    switch($field) {
                        case 'observacao':
                            $novaOrdenacao[] = 'CDCDTOBSERVACAO' . ($type > 0 ? ' ASC' : ' DESC');
                            break;
                        case 'descricao':
                            $novaOrdenacao[] = 'CDCDTDESCRICAO' . ($type > 0 ? ' ASC' : ' DESC');
                            break;
                        case 'codigo':
                            $novaOrdenacao[] = 'CDCDTCODIGO' . ($type > 0 ? ' ASC' : ' DESC');
                            break;
                    }
                }

                // verifica se adiciou campos para ordenacao
                if(count($novaOrdenacao) <= 0)
                    $novaOrdenacao = array('CDCDTDESCRICAO ASC');

                // Atribui a nova ordenacao
                $ordenacao = $novaOrdenacao;
            }

            // Executa a busca com as parametros definidos
            $resBusca = $this->_dataModule->qryListaCadastrosTestes($campoBusca, $valorBusca, $ordenacao, $this->_dataModule->camposForm['perPage'], $this->_dataModule->camposForm['offset']);

            // Prepara a resposta para a busca
            $dataTable = array(); // Armazena os registros encontrados

            // Verifica se existem registros
            if($resBusca->count()) {

                // Prepara os dados para a resposta
                foreach ($resBusca as $row) {
                    $dataTable[] = array('codigo'     => $row->CDCDTCODIGO,
                                         'descricao'  => $row->CDCDTDESCRICAO,
                                         'observacao' => $row->CDCDTOBSERVACAO);
                }
            }

            // responde com a mensagem JSON de grid
            // OBS: As mensagens de GRID não são adicionadas ao JSONMessage, ao invés disto
            // a Saída é dada diretamente.
            echo Zend_Json::encode(JSONMessageFactory::factoryGrid($dataTable, $this->_dataModule->qryBuscaNumeroRegistros($campoBusca, $valorBusca), count($dataTable)));

        } catch(DSINWebAppsException $dwaex) {

            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException($dwaex->getMessage()));

        } catch(Exception $ex) {

            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException("Ocorreram erros no processamento da Página"));
        }

        exit;
    }

    /**
     * Recebe as solicitacoes do tipo get
     *
     * O Objetivo desta solicitação é receber requisicoes para busca de um unico registro
     * recebendo para isto os valores dos campos que identificacam o registro (chave primária).
     *
     */
    public function getAction() {

        try {

            // Busca o registro Solicitado
            $cadastroTeste = $this->_dataModule->qryBuscaCadastroTeste((int)$this->_dataModule->camposForm['codigo']);

            // Verifica se encontrou o registro Registro encontrado
            if($cadastroTeste) {

                // Prenche os campos com os dados do banco de dados
                $this->_dataModule->camposForm['codigo']     = $cadastroTeste->CDCDTCODIGO;
                $this->_dataModule->camposForm['descricao']  = $cadastroTeste->CDCDTDESCRICAO;
                $this->_dataModule->camposForm['observacao'] = $cadastroTeste->CDCDTOBSERVACAO;

                // Verifica e atribui permissoes de botoes para o formulario
                $this->_dataModule->atribuiPermissoesBotoes(FORM_UPDATE);

                // Adiciona a mensagem JSON para preenchimento do formulario
                JSONMessage::getInstance()->add(JSONMessageFactory::factoryForm($this->_dataModule->camposForm, 'descricao'));

            } else { // Registro nao encontrado

                // Verifica e atribui permissoes de botoes para o formulario
                $this->_dataModule->atribuiPermissoesBotoes(FORM_ADD);

                // Adiciona a mensagem JSON para preenchimento do formulario
                JSONMessage::getInstance()->add(JSONMessageFactory::factoryForm($this->_dataModule->camposForm));

            }

        // Pega a excecao lançada
        } catch(DSINWebAppsException $dwaex) {
            
            // Transforma a excecao lancada no formato entendido pelo cliente
            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException($dwaex->getMessage()));

        }

        echo JSONMessage::getInstance();

    }
    

    /**
     * Recebe as solicitacoes do tipo post
     *
     * Este mensagem corresponde a uma inclusao de registro
     *
     */
    public function postAction() {

        try {

            // Valida o Formulario
            $validacaoFormulario = $this->validaFormulario();

            // Verifica se ocorreram erros na validacao
            if(!$validacaoFormulario) {

                try {

                    // Inicia a transacao
                    Zend_Registry::getInstance()->dbAdapter->beginTransaction();

                    // Cria o registro 
                    $cadastroTeste = $this->_dataModule->createRow();

                    $cadastroTeste->CDCDTCODIGO     = $this->_dataModule->camposForm['codigo'];
                    $cadastroTeste->CDCDTDESCRICAO  = $this->_dataModule->camposForm['descricao'];
                    $cadastroTeste->CDCDTOBSERVACAO = $this->_dataModule->camposForm['observacao'];

                    // Salva o registro no banco de dados
                    $cadastroTeste->save();

                    // Registra a acao do usuario no banco de dados
                    UserLogger::log(1, 'Cod: ' . $cadastroTeste->CDCDTCODIGO);

                    // Comita a transacao
                    Zend_Registry::getInstance()->dbAdapter->commit();


                // Recupera a Excecao Mais Geral
                // Pode ser expecificada a excecao caso seja necessário algum tipo de tratamento, neste caso
                // o rollback deve ser dado e uma nova excecao DSINWebAppsException deve ser lancada
                } catch(Exception $ex) {

                    // da roll back na transacao desconsiderando todas as operacoes executadas no banco de dados
                    Zend_Registry::getInstance()->dbAdapter->rollBack();

                    // Lanca excecao para ser recuperada fora do bloco
                    throw new DSINWebAppsException(DSINWebAppsException::ERRO_DESCONHECIDO, 'Code:' . $ex->getCode());
                }

                // Envia uma mensagem de Redirecionamento para a Interface
                JSONMessage::getInstance()->add(JSONMessageFactory::factoryClearForm('codigo'));

            } else { // Ocorreram erros na validacao do formulario

                // Envia uma mensagem de Redirecionamento para a Interface
                JSONMessage::getInstance()->add(JSONMessageFactory::factoryFormValidation($validacaoFormulario));
            }


        } catch(DSINWebAppsException $dwaex) {

            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException($dwaex->getMessage(), true));

        } catch(Exception $ex) {

            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException("Ocorreram erros no processamento da Página"));
        }

        echo JSONMessage::getInstance();
    }
    
    /**
     * Recebe as solicitacoes do tipo put
     *
     * O Objetivo desta solicitação é receber requisicoes para alteração de registro
     *
     */
    public function putAction() {
        
        try {

            // Valida os dados do formulario enviado
            $validacaoFormulario = $this->validaFormulario();

            // Valida o Formulário enviado
            if(!$validacaoFormulario) {

                // Busca o registro para alteração dos dados
                $cadastroTeste = $this->_dataModule->qryBuscaCadastroTeste((int)$this->_dataModule->camposForm['codigo']);

                // Verifica se o registro foi encontrado
                if($cadastroTeste) {

                    try {


                        // Inicia a transacao
                        Zend_Registry::getInstance()->dbAdapter->beginTransaction();

                        $oldRec = $cadastroTeste->toArray();

                        // Altera os dados
                        $cadastroTeste->CDCDTDESCRICAO  = $this->_dataModule->camposForm['descricao'];
                        $cadastroTeste->CDCDTOBSERVACAO = $this->_dataModule->camposForm['observacao'];

                        // Salva o registro
                        $cadastroTeste->save();

                        // Registra a acao do usuario no banco de dados
                        UserLogger::log(2, 'Cod: ' . $cadastroTeste->CDCDTCODIGO, null, Util::dataDiff($oldRec, $cadastroTeste->toArray()));

                        // Comita a transacao
                        Zend_Registry::getInstance()->dbAdapter->commit();

                    // Recupera a Excecao Mais Geral
                    // Pode ser expecificada a excecao caso seja necessário algum tipo de tratamento, neste caso
                    // o rollback deve ser dado e uma nova excecao DSINWebAppsException deve ser lancada
                    } catch(Exception $ex) {

                        // da roll back na transacao desconsiderando todas as operacoes executadas no banco de dados
                        Zend_Registry::getInstance()->dbAdapter->rollBack();

                        // Lanca excecao para ser recuperada fora do bloco
                        throw new DSINWebAppsException(DSINWebAppsException::ERRO_DESCONHECIDO, 'Code:' . $ex->getCode());
                    }

                } else { // Registro não encontrado

                    // lanca excecao
                    throw new DSINWebAppsException(DSINWebAppsException::ERRO_REGISTRO_NAO_ENCONTRADO);

                }

                // Envia uma mensagem de Redirecionamento para a Interface
                JSONMessage::getInstance()->add(JSONMessageFactory::factoryClearForm('codigo'));

            } else { // Ocorreram erros na validacao do formulario

                // Envia uma mensagem de Redirecionamento para a Interface
                JSONMessage::getInstance()->add(JSONMessageFactory::factoryFormValidation($validacaoFormulario));
            }


        } catch(DSINWebAppsException $dwaex) {

            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException($dwaex->getMessage()));

        } catch(Exception $ex) {

            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException("Ocorreram erros no processamento da Página"));
        }

        // imprime a resposta JSON
        echo JSONMessage::getInstance();
    }
    
    /**
     * Recebe as solicitacoes do tipo delete
     *
     * O Objetivo desta solicitação é receber requisicoes para exclusao de registro
     *
     */
    public function deleteAction() {
        try {

            // Busca o registro para alteração dos dados
            $cadastroTeste = $this->_dataModule->qryBuscaCadastroTeste((int)$this->_dataModule->camposForm['codigo']);

            // Verifica se o registro foi encontrado
            if($cadastroTeste) {

                try {

                    // Inicia a transacao
                    Zend_Registry::getInstance()->dbAdapter->beginTransaction();

                    // Recupera os dados atuais do registro
                    $oldRec = $cadastroTeste->toArray();

                    // Deleta or egistro
                    $cadastroTeste->delete();

                    // Registra a acao do usuario no banco de dados
                    UserLogger::log(3, 'Cod: ' . $oldRec['CDCDTCODIGO'], null, Util::dataDiff($oldRec, array()));

                    // Comita a transacao
                    Zend_Registry::getInstance()->dbAdapter->commit();

                // Recupera a Excecao Mais Geral
                // Pode ser expecificada a excecao caso seja necessário algum tipo de tratamento, neste caso
                // o rollback deve ser dado e uma nova excecao DSINWebAppsException deve ser lancada
                } catch(Exception $ex) {

                    // da roll back na transacao desconsiderando todas as operacoes executadas no banco de dados
                    Zend_Registry::getInstance()->dbAdapter->rollBack();

                    // Lanca excecao para ser recuperada fora do bloco
                    throw new DSINWebAppsException(DSINWebAppsException::ERRO_DESCONHECIDO, 'Code:' . $ex->getCode());
                }

            } else { // Registro não encontrado

                // lanca excecao
                throw new DSINWebAppsException(DSINWebAppsException::ERRO_REGISTRO_NAO_ENCONTRADO, 'Código: ' . $this->_dataModule->camposForm['codigo']);

            }

            // Envia uma mensagem de Redirecionamento para a Interface
            JSONMessage::getInstance()->add(JSONMessageFactory::factoryClearForm('codigo'));

        } catch(DSINWebAppsException $dwaex) {

            // Adiciona Mensagem de excecao
            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException($dwaex->getMessage()));

        } catch(Exception $ex) {

            // Adiciona Mensagem de excecao
            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException("Ocorreram erros no processamento da Página"));
        }

        // imprime a resposta JSON
        echo JSONMessage::getInstance();
    }

    /**
     * Valida as informações recebidas
     *
     * Este método é obrigatório para todos os actions que recebem informações
     * independente da fonte ou do metodo
     *
     * @params
     * 
     */
    private function validaFormulario() {

        $erros = array();

        if(empty($this->_dataModule->camposForm['codigo']))
            $erros[] = FormValidatorHelper::addError('codigo', FormValidatorHelper::ERROR_CODE_EMPTY_FIELD);
        else
            if(!is_numeric($this->_dataModule->camposForm['codigo']))
                $erros[] = FormValidatorHelper::addError('codigo', FormValidatorHelper::ERROR_CODE_INCORRECT_DATA_TYPE);

        if(empty($this->_dataModule->camposForm['descricao']))
            $erros[] = FormValidatorHelper::addError('descricao', FormValidatorHelper::ERROR_CODE_EMPTY_FIELD);

        if(empty($this->_dataModule->camposForm['observacao']))
            $erros[] = FormValidatorHelper::addError('observacao', FormValidatorHelper::ERROR_CODE_EMPTY_FIELD);

        return $erros;
    }
}