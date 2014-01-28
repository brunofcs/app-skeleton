<?php
require_once 'DefaultViewController.php';

require_once 'dataModules/WebAppsDataModule.php';

/**
 * IndexController is the default controller for this application
 * 
 * Notice that we do not have to require 'Zend/Controller/Action.php', this
 * is because our application is using "autoloading" in the bootstrap.
 *
 * @see http://framework.zend.com/manual/en/zend.loader.html#zend.loader.load.autoload
 */
class WebAppsController extends DefaultViewController {

    // Data Model do Controller
    private $_dataModule = null;

    public function preDispatch() {

        // Verifica se o login foi efetuado
        parent::preDispatch();
    }

    /**
     * Inicializacao do Controller
     * 
     */
    public function init() {

        // Instancia o ModuleData da Rotina
        $this->_dataModule = new WebAppsDataModule();

    }

    /**
     * The "index" action is the default action for all controllers. This 
     * will be the landing page of your application.
     *
     * Assuming the default route and default router, this action is dispatched 
     * via the following urls:
     *   /
     *   /index/
     *   /index/index
     *
     * @return void
     */
    public function indexAction() {

        // Recupera o Usuario
        $usuario = Zend_Auth::getInstance()->getIdentity();

        // Disponibiliza para view o nome dele
        $this->view->usuario = $usuario['CDUSNOME'];

        // Busca e prepara os menus principais para montagem dinamica na view
        $qryResultado = $this->_dataModule->qryBuscaMenus(true);

        // Disponibiliza os menus principais para a view
        $this->view->menusPrincipais = $qryResultado->toArray();

        // Busca os SubMenus
        $qryResultado = $this->_dataModule->qryBuscaMenus(false);

        $subMenus = array();
        foreach ($qryResultado as $key => $menu)
            $subMenus[$menu->CDMNUPAI][] = $menu->toArray();

        // Disponibiliza os submenus apra a view
        $this->view->subMenus = $subMenus;
        
    }
}