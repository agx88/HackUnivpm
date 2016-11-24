<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected $_logger;
	protected $_view;

	protected function _initLogging()
    {
        $logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Firebug();
        $logger->addWriter($writer);

        Zend_Registry::set('log', $logger);
        
        $this->_logger = $logger;
    		$this->_logger->info('Bootstrap ' . __METHOD__);
    }

    protected function _initRequest()
	// Aggiunge un'istanza di Zend_Controller_Request_Http nel Front_Controller
	// che permette di utilizzare l'helper baseUrl() nel Bootstrap.php
    	// Necessario solo se la Document-root di Apache non è la cartella public/
    {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        $request = new Zend_Controller_Request_Http();
        $front->setRequest($request);
    }

    protected function _initViewSettings()
    {
        $this->bootstrap('view');
        $this->_view = $this->getResource('view');
        $this->_view->headMeta()->setCharset('UTF-8');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'it-IT');
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/materialize.css'));
		$this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/page-center.css'));
		$this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/prism.css'));
		$this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/style.css'));
        
    }
	
	protected function _initDefaultModuleAutoloader() //Zend deve sapere come caricare la libreria App, non standard
	//Zend considera di default la libreria App dentro la cartella library
    { //Comunichiamo a Zend che c'è un nuovo componente (App)in library
    	$loader = Zend_Loader_Autoloader::getInstance(); //Estraiamo dalla nostra applicazione(getInstance) l'istanza dell'Autoloader(oggetto singletone)
		$loader->registerNamespace('App_'); //Attiviamo registerNamespace al quale passiamo il nome della cartella seguito da _ 
		//-> _ verrà utilizzato per individuare il percorso da seguire a partire dal nome della classe che stiamo definendo
		//Il nome della classe corrisponde al path -> Con tale configurazione diciamo a Zend che tutte le volte che facciamo
		// una new di una classe che comincia con "App",
		// il file associato a tale classe sta dentro la cartella library/App
$this->getResourceLoader() ->addResourceType('modelResource','models/resources','Resource'); 
  	}
	
	protected function _initDbParms() 
    {
    	include_once (APPLICATION_PATH . '/../../include/connect.php'); 
		$db = new Zend_Db_Adapter_Pdo_Mysql(array( 
    			'host'     => $HOST,
    			'username' => $USER,
    			'password' => $PASSWORD,
    			'dbname'   => $DB
				));  
		Zend_Db_Table_Abstract::setDefaultAdapter($db); 
	}
	
	
}
