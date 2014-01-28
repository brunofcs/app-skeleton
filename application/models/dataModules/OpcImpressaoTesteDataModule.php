<?php
require_once "AppDataModule.php";

/**
 * Data Model da rotina OpcCadTeste
 *
 */
class OpcImpressaoTesteDataModule extends AppDataModule {

    // Campos existentes no formulário e requisicoes
    public $camposForm = array('controller'=>'', 'tipoPeriodo'=>'', 'dataInicial'=>'', 'dataFinal'=>'', 'ano'=>'',
    						   'referencia1'=>'', 'anoRef1'=>'', 'referencia2'=>'', 'anoRef2'=>'', 'formato'=>'',
    	                       'btnImprimir'=>'');

    /**
     * Metodo apra criação de um registro da tabela controlada pelo DataModule
     *
     */
    public function createRow() {}

	/**
	 *
	 * Query para busca das permissões dos botoes da rotina correspondente a este data module
	 */
	public function atribuiPermissoesBotoes($acao) {

		// Recupera o Usuario Logado
		$usuario = Zend_Auth::getInstance()->getIdentity();

		// Busca a autorizacao
		$autorizacao = $this->getPermissaoBotoes($usuario['CDUSGRUPO'], $this->camposForm['controller']);

		// Atribui a autorizacao baseado na acao e nas autorizações dadas no grupo
		$this->camposForm['btnImprimir'] = (($acao & FORM_PRINT)    && $autorizacao['LCAUTIMPRIMIR'] ? TRUE : FALSE);

	}

    /**
     * Busca registros Relatorio
     *
     */
    public function qryBuscaRegistros() {

		// Recupera os Models Necessários
	    $menuTable  = ModelsHelper::getInstance()->MenuTable; 

		// Cria o objeto para construcao da consulta
		$select = $menuTable->select();

		// Constroi a Query
    	$select->from($menuTable->getTable(), array('*'));
    	$select->order('CDMNUORDEM');

		// Executa a SQL
		$resultado = $menuTable->fetchAll($select);

		return $resultado;

    }
}