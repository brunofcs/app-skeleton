<?php
require_once "AppDataModule.php";

/**
 * Data Model da rotina OpcCadTeste
 *
 */
class OpcCadTesteDataModule extends AppDataModule {

    // Campos existentes no formulário e requisicoes
    public $camposForm = array('codigo'=>'', 'descricao'=>'', 'observacao'=>'',
    	                       'btnIncluir'=>'', 'btnAlterar'=>'', 'btnExcluir'=>'',

    	                       'page'=>'', 'perPage'=>'10', 'offset'=>'', 
    	                       'queries'=>array(), 'sorts'=>array());

    /**
     * Metodo apra criação de um registro da tabela controlada pelo DataModule
     *
     */
    public function createRow() {

    	// Cria um novo registro
    	return ModelsHelper::getInstance()->CadastroTesteTable->createRow();

    }

    /**
     *
     * Query para Busca do proximo codigo para cadastro
     *
     */
	public function qryBuscaProximoCodigo() {

		// Recupera os Models Necessários
	    $modelsHelper  = ModelsHelper::getInstance();
	    $cadastroTable = $modelsHelper->CadastroTesteTable;

		// Cria o objeto para construcao da consulta
		$select = $cadastroTable->select();

		// Constroi a Query
    	$select->from($cadastroTable->getTable(), array('MAX(CDCDTCODIGO) as PROXIMO'));

		// Executa a SQL
		$resultado = $cadastroTable->fetchRow($select);

		// Retorna o proximo codigo
		if($resultado->PROXIMO === NULL)
			return  1;
		else
			return $resultado->PROXIMO+1;
	}

	/**
	 *
	 * Query para busca das permissões dos botoes da rotina correspondente a este data module
	 */
	public function atribuiPermissoesBotoes($acao) {

		// Recupera o Usuario Logado
		$usuario = Zend_Auth::getInstance()->getIdentity();

		// Busca a autorizacao
		$autorizacao = $this->getPermissaoBotoes($usuario['CDUSGRUPO'], 'opcCadTeste');

		// Atribui a autorizacao baseado na acao e nas autorizações dadas no grupo
		$this->camposForm['btnIncluir'] = (($acao & FORM_ADD)    && $autorizacao['LCAUTINCLUIR'] ? TRUE : FALSE);
		$this->camposForm['btnAlterar'] = (($acao & FORM_UPDATE) && $autorizacao['LCAUTALTERAR'] ? TRUE : FALSE);
		$this->camposForm['btnExcluir'] = (($acao & FORM_UPDATE) && $autorizacao['LCAUTEXCLUIR'] ? TRUE : FALSE);

	}

	/**
	 *
	 * Query para busca de um unico registro baseado em sua chave primária
	 *
	 */
	public function qryBuscaCadastroTeste($codigo) {

		// Recupera os Models Necessários
	    $modelsHelper  = ModelsHelper::getInstance();
	    $cadastroTable = $modelsHelper->CadastroTesteTable;

	    // Busca o registro utilizando o metodo find (Busca pelas chaves)
	    $resultadoBusca = $cadastroTable->find((int)$codigo);

	    // Verifica se o registro foi encontrado
	    if($resultadoBusca->count() > 0)
	    	return $resultadoBusca->current(); // Retorna o registro encontrado

	   	return NULL; // Retorna NULL 

	}

	/**
	 *
	 * Query para busca de uma lista de registros
	 * 
	 */
	public function qryListaCadastrosTestes($campoBusca, $valorBusca, $ordenacao, $qtdeRegistros, $offset) {

		$cadastroTesteTable = ModelsHelper::getInstance()->CadastroTesteTable;

		return $cadastroTesteTable->fetchAll($campoBusca . ' LIKE \'%' . $valorBusca . '%\'', $ordenacao, $qtdeRegistros, $offset);

	}

	/**
	 *
	 * Query para busca do numero de registros existens baseado em um determinado filtro
	 *
	 */
	public function qryBuscaNumeroRegistros($campoBusca='', $valorBusca='') {

		// Recupera os Models Necessários
	    $cadastroTable  = ModelsHelper::getInstance()->CadastroTesteTable;

		// Cria o objeto para construcao da consulta
		$select = $cadastroTable->select();

		// Constroi a Query
    	$select->from($cadastroTable->getTable(), array('COUNT(*) as QTDE'));

    	if($campoBusca && $valorBusca)
    		$select->where($campoBusca . ' LIKE \'%' . $valorBusca . '%\'');

		// Executa a SQL
		$resultado = $cadastroTable->fetchRow($select);

		// Retorna a quantidade de registro
		return $resultado->QTDE;

	}
}