<?php
//L'oggetto che gestisce l'autorizzazione è un servizio (non un controller, un model o una vista)
//Definisce tutte le funzionalità di autorizzazione basandosi sia su Zend_Auth che su altro

class Application_Service_Auth //"Service" al singolare dentro la cartella "services"
{
    protected $_publicModel;
    protected $_auth; 
    
    //Zend sa dove sta ma non capisce che classe è -> Non metto la init ma la __construct() (sfrutto le caratteristiche del PHP)
    //è una classe esterna a Zend -> Per cui non posso usare la init e allora ricorro alla __construct()
    public function __construct() 
    {
        $this->_publicModel = new Application_Model_Public(); //Dovendo autenticare le credenziali, ho bisogno di un collegamento
        // con la tabella utenti -> Il costruttore istanzia nella proprietà _publicModel un oggetto di tipo Application_Model_Public
		//Application_Model_Public l'avevamo già creata in precedenza (era quello che ci consentiva di estrarre tutte le 
		//sottocategorie che ci consentiva di estrarre un prodotto nelal cartella prodotti) -> Possiamo ora inserire la getUtenteByUsername() nel model  
    }
    
    public function authenticate($credentials) //Realizza l'autenticazione
    //La utilizziamo nel PublicController in authenticateAction che si scatena al submit della form
    {
    	//$adapter -> Canale di autenticazione
        $adapter = $this->getAuthAdapter($credentials);//Definisce l'adapter, la risorsa, la tabella rispetto alla quale andranno confrontate 
        //le credenziali che l'utente fornirà tramite la form di login -> in $adapter troviamo il risultato dell'attivazione
        //del metodo getAuthAdapter al quale passiamo $credentials, le credenziali cioè lo username e la pass estratte dalla form
        //La getAuthAdapter() è l'ultima funzione sotto descritta
        $auth    = $this->getAuth();//Crea una nuova istanza di Zend_Auth che contiene le funzionalità per l'autenticazione
        $result  = $auth->authenticate($adapter); //Gli passo le credenziali! Verifica se corrispondono le credenziali

        if (!$result->isValid()) {  
            return false; /*Se sul risultato della attivazione della authenticate attiviamo 
		 * la isValid() possiamo ottenere come risultato true o false: true->se l'accoppiamento ha portato ad una corrispondenza
		 * false-> se la corrispondenza non c'è. Dopo aver autenticato le credenziali fornite dall'utente, se non sono valide 
		 * la funzione autheniicate ritorna false, altrimenti attiviamo l'adminModel e di conseguenza la getUserByName() 
		 * passandogli come credenziali lo username -> */
        }
        $user = $this->_publicModel->getUtenteByUsername($credentials['username']); //-> Così prendo tutte le info dell'utente e 
        //memorizzo tutta la tupla in $user
        $auth->getStorage()->write($user); /*Con il metodo write() andiamo a memorizzare l'utente nella componente storage
		 * dell'oggetto Zend_Auth*/
        return true;
    }

	public function getAuth() //Prende l'oggetto predefinito Zend_Auth e lo rende disponibile all'authenticate
    //Se l'oggetto Zend_Auth non è stato già istanziato nella proprietà _auth allora lo istanzio, altrimenti la funzione non fa nulla
    {
        if (null === $this->_auth) { /*Quando utilizziamo Application_Service_Auth potremmo più volte aver bisogno di Zend_Auth
		 * per cui wrappo l'acquisizione in una if che mi dice:"se l'ho già istanziato (_auth ha già un valore) allora non
		 * lo istanzio ancora e prendo tale valore, altrimenti catturo l'oggetto Zend_Auth e lo assegno a _auth" */
            $this->_auth = Zend_Auth::getInstance(); //Risultato del metodo getAuth() -> Estrae l'istanza di Zend_Auth
            //Perchè non fa una new di un nuovo oggetto come nell'adapter? Perchè Zend_Auth è automaticamente istanziato dal 
            /*bootstrap dell'applicazione nel workflow di Zend. -> A prescindere che mettiamo in piedi o meno un meccanismo
			 * di autenticazione, Zend crea un istanza di Zend_Auth (che è anche singletone)
			 * */
        }
        return $this->_auth; //IMPORTANTE: Il risultato ottenuto è l'oggetto di autenticazione in cui sono definite una serie
        /*di proprietà e metodi fra cui la isValid().(* vedi sopra)*/ 
    }

	public function getIdentity() //Metodi da utilizzare nei controller -> Consente di estrarre informazioni (nome,cognome,...)
     //quando l'utente si autentica
        {
        $auth = $this->getAuth();// Prende l'oggetto Zend_Auth
        if ($auth->hasIdentity()) { //hasIdentity() vede se c'è un utente registrato 
            return $auth->getIdentity(); //Se c'è ritorna l'identità (l'identità è stata già settata da Zend_Auth nella authenticate) 
        }
        return false;
    }
		
	 public function clear() //Cancella l'utente autenticato (Session destroy)
    {
        $this->getAuth()->clearIdentity(); //Ogni oggetto di Zend_Auth ha un metodo predefinito che è la clearIdentity()
        //che azzera tutte le credenziali dell'utente che vuole scollegarsi
    }
	
	public function getAuthAdapter($values) //Serve solo all'authenticate per definire il canale di autenticazione
    {
		$authAdapter = new Zend_Auth_Adapter_DbTable( //Crea una nuova istanza di Zend_Auth_Adapter_DbTable, una classe che crea 
		//oggetti che mappano Zend_Auth con una tabella del nostro ORM
			Zend_Db_Table_Abstract::getDefaultAdapter(), //Gli passo 4 parametri: 1) PDO (canale di connessione tra applicazione e MySQL)
			//Il PDO è un oggetto complesso utilizzato da Zend_DbTable_Abstract per accedere alle tabelle MySQL -> 
			//Ogni istanza di Zend_Db-Table_Abstract, quando viene usata, ha al suo interno l'oggetto PDO -> 
			//Posso prendere la componente PDO di Zend_DbTable_Abstract senza ridifenirlo (creato in automatico dall'application.ini)
			'utenti', // 2) Nome della tabella che devo andare a prendere all'interno del DB su cui devo confrontare user e pass
			'username', //3) Colonna della tabella con le quali vanno confrontate le credenziai che l'utente specifica nella form
			'password'// 4) Colonna della tabella con le quali vanno confrontate le credenziai che l'utente specifica nella form
		);
		$authAdapter->setIdentity($values['username']); //Mappa nella attributo definito come 3), il dato che l'utente fornisce
		//Nell'adattatore metti in corrispondenza 3) con il parametro fornito dall'utente
		$authAdapter->setCredential($values['password']); //Sono le credenziali -> Stessa cosa di prima
        return $authAdapter; //Definiamo identità e credenziali e li ritorniamo
    }
}
    