<?php

class Application_Resource_Cassonetti extends Zend_Db_Table_Abstract
{
	
	protected $_name    = 'cassonetti';
    protected $_primary  = 'Id';
    protected $_rowClass = 'Application_Resource_Cassonetti_Item';
	   
    public function init(){
    	
    }

}