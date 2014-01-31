<?php
require_once APPLICATION_PATH . '/adapter/CustomAuthAdapter.php';

require_once 'FormValidatorHelper.php';
require_once 'DefaultViewController.php';

/**
 * Controller de Autenticação
 *
 * OBS: Toda classe não rest deve extender DefaultViewController
 */
class AutenticacaoController extends DefaultViewController
{

    // Campos existentes no formulário e requisicoes
    public $_camposForm = array('usuario'=>'', 'senha'=>'');

    public function preDispatch() {

        // Extrai caracteres invalidos de dados enviados
        $this->_request->setParams(SecurityHelper::secureEnvData($this->_request->getParams()));

        // Extrai caracteres invalidos de dados enviados
        $this->_camposForm = SecurityHelper::preparaDadosFormulario($this->_camposForm, $this->_request->getParams());

    }

    // Inicializa o Controller
    public function init() {

        // Sem correspondente visual (não rederiza nenhuma pagina)
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }
    
    /**
     * Processa o Login para acesso ao sistema
     * 
     * Este método deve validar o usuário, data de vencimento, bloqueio e todas
     * as restrições de acesso utilizadas no sistema
     *
     */
    public function loginAction() {

        try {

            // Valida os dados enviados
            $validacaoFormulario = $this->validaFormulario();

            // Verica se os dados enviados são valido
            if(!$validacaoFormulario) {

                // Cria uma Instância do Autenticador
                $authAdapter = new CustomAuthAdapter($this->_camposForm['usuario'], $this->_camposForm['senha']);
            
                // Executa a Autenticacao do Usuário e persiste o mesmo em sessao 
                // caso o usuario e senha estejam corretos
                $authResult = @Zend_Auth::getInstance()->authenticate($authAdapter);

                // Verifica se o login foi executado com sucesso
                if($authResult->isValid()) {

                    // Envia uma mensagem de Redirecionamento para a Interface
                    JSONMessage::getInstance()->add(JSONMessageFactory::factoryRedir('/webApps'));

                // Login nao efetuado
                } else {

                    // Limpa a autenticacao da sessao corrente
                    Zend_Auth::getInstance()->clearIdentity();

                    // Adiciona a Excecao
                    JSONMessage::getInstance()->add(JSONMessageFactory::factoryException("Login não efetuado"));

                }
            } else {

                // Envia uma mensagem de Redirecionamento para a Interface
                JSONMessage::getInstance()->add(JSONMessageFactory::factoryFormValidation($validacaoFormulario));

            }

        } catch(Exception $ex) {

            

            // Limpa a autenticacao da sessao corrente
            Zend_Auth::getInstance()->clearIdentity();

            // Envia uma mensagem de excecao para o Cliente
            JSONMessage::getInstance()->add(JSONMessageFactory::factoryException($ex->getMessage()));

        }

        // Envia as mensagens adicionadas para o cliente
        echo JSONMessage::getInstance();

        exit;
    }

    /**
     * Processa o Logout da Aplicação
     * 
     *
     */
    public function logoutAction() {

        // Limpa a autenticacao da sessao corrente
        Zend_Auth::getInstance()->clearIdentity();

        // Redireciona para principal da aplicacao
        $this->_redirect("/");

        exit;
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

        if(empty($this->_camposForm['usuario']))
            $erros[] = FormValidatorHelper::addError('usuario', FormValidatorHelper::ERROR_CODE_EMPTY_FIELD);
        else
            if(FormValidatorHelper::validateEmail($this->_camposForm['usuario']) == false)
                $erros[] = FormValidatorHelper::addError('usuario', FormValidatorHelper::ERROR_CODE_INCORRECT_EMAIL_FORMAT);

        if(empty($this->_camposForm['senha']))
            $erros[] = FormValidatorHelper::addError('senha', FormValidatorHelper::ERROR_CODE_EMPTY_FIELD);

        return $erros;
    }
}