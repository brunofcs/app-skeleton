<?php
/*
 * Classe AppDataModule
 *
 * Todos os data modules da aplicacao devem herdar esta classe
 *
 */
abstract class AppDataModule {


	/*
	 * getPermissoesBotoes
	 *
	 * @param grupo - Grupo ao qual o usuario pertence
	 * @param menu  - Menu ao qual está verificando as permissoes dos botoes
	 *
	 * @return Array com as autorizações ou null caso não sejam encontradas
	 */
	public function getPermissaoBotoes($grupo, $menu) {

		// Busca o registro de autorização
		$resultado = ModelsHelper::getInstance()->AutorizacaoTable->find($grupo, $menu);

		// Verifica se o registro de autorizacao foi encontrado
		if($resultado->count()) {

			// Retorna o registro em formato de array
			$resultado = $resultado->toArray();
			return $resultado[0];
		}

		// Registro de autorizacao nao encontrado
		return null;
	}

	/**
	 * Metodo abstrato para criacao de registro, deve ser implementado pela classe filha
	 */
	abstract public function createRow();

}