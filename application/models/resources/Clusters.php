<?php


class Application_Resource_Clusters extends Zend_Db_Table_Abstract
{
	
	protected $_name    = 'clusters';
    protected $_primary  = 'Id';
    protected $_rowClass = 'Application_Resource_Clusters_Item';
	   
    public function init(){
    	
    }

}