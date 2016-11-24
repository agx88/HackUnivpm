<?php

class Application_Resource_Camion extends Zend_Db_Table_Abstract
{
	
	protected $_name    = 'camion';
    protected $_primary  = 'Id';
    protected $_rowClass = 'Application_Resource_Camion_Item';
	   
    public function init(){
    	
    }

}