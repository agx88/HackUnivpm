<?php

class PublicController extends Zend_Controller_Action
{
	
    public function init()
    { 
		$this->_helper->layout->setLayout('index');
    }

    public function indexAction()
    {
    	  $this->_publicModel=new Application_Model_Public(); //Istanzio il Model
    }
}

