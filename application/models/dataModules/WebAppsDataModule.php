<?php
require_once "AppDataModule.php";

/**
 * Data Model da rotina OpcCadTeste
 *
 */
class WebAppsDataModule extends AppDataModule {

    // Campos existentes no formulário e requisicoes
    public $camposForm = array();

    /**
     * 
     * Implementacao vazia já que este datamodule não insere registros
     */
    public function createRow() {}

    /**
     *
     * Query para Busca dos Menus Principais da Aplicacao
     *
     */
	public function qryBuscaMenus($menusPrincipais) {

		// Recupera os Models Necessários
	    $menuTable  = ModelsHelper::getInstance()->MenuTable; 

		// Cria o objeto para construcao da consulta
		$select = $menuTable->select();

		// Constroi a Query
    	$select->from($menuTable->getTable(), array('CDMNUMENU', 'CDMNUTIPO', 'CDMNUNOME', 'CDMNUPAI'));
    	$select->where($menusPrincipais ? 'CDMNUPAI IS NULL' : 'CDMNUPAI IS NOT NULL');
    	$select->order('CDMNUORDEM');

		// Executa a SQL
		$resultado = $menuTable->fetchAll($select);

		return $resultado;
	}

}