<?php

class Application_Resource_Tipologie extends Zend_Db_Table_Abstract
{
	
	protected $_name    = 'tipologie';
    protected $_primary  = 'Id';
    protected $_rowClass = 'Application_Resource_Tipologie_Item';
	   
    public function init(){
    	
    }

}