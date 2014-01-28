<?php

class Rest_TesteController extends Zend_Rest_Controller
{

    public function preDispatch() {
                /* @var $ajaxContext Zend_Controller_Action_Helper_AjaxContext */
        $ajaxContext = $this->_helper->getHelper('AjaxContext')->addActionContext('index', 'json'); 
        // $ajaxContext->addActionContext('get', 'html');
        // $ajaxContext->addActionContext('post', 'json');
        // $ajaxContext->addActionContext('put', 'json');
        // $ajaxContext->addActionContext('delete', 'json');
        // $ajaxContext->initContext();
        $this->_helper->ajaxContext->initContext();

    }
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();



    }

    public function indexAction()
    {
         

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
    
    public function postAction()
    {
        $this->getResponse()
            ->appendBody("From postAction() creating the requested article");
    }
    
    public function putAction()
    {
        $this->getResponse()
            ->appendBody("From putAction() updating the requested article");
    }
    
    public function deleteAction()
    {
        $this->getResponse()
            ->appendBody("From deleteAction() deleting the requested article");
    }

    public function headAction(){
 
        #$this->_forward('index');
 
    }
}