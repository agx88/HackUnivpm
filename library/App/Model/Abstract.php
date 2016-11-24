<?php //Definiamo la classe astratta (La parte nera sulla figura dei model)

abstract class App_Model_Abstract //è la classe astratta per i modelli -> definiamo tutte le proprietà e i metodi che dovranno essere successivamente utilizzati
//è una classe astratta -> Non deriviamo direttamente degli oggetti ma solo delle sottoclassi-> La classe non deve generare oggetti
//ma riunire proprietà comuni
{	
	protected $_resources = array(); //Si definisce un array vuoto protetto cosicchè solo le classi discendenti possono vedere
	
	
	//IMPORTANTE
	//Il model deve caricare gli oggetti che rappresentano tutte le tabelle che poi devono essere utilizzate
	//Caricare l'oggetto significa dire a Zend dove si trova il file della classe e fare una new sulla classe stessa
	
	//IN QUESTO CASO NOI DEFINIAMO UNA CLASSE ASTRATTA DA ZERO 
	
	//GETRESOURCE -> Passeremo il nome della risorsa (Utenti, Notifiche, Commenti ) al model che richiama tale metodo (User, Staff,...)
	// -> Individuerà il file che contiene la  definizione della classe delgli Utenti, delle Notifiche, dei Commenti,...
	// -> Farà una new della classe definita all'interno del file 
	// -> Ritornerà al chiamante la classe che potrà essere utilizzata
	
	
	
	public function getResource($name) //Definiamo il meccanismo di istanziazione di un oggetto a partire da
	//una classe che rappresenta una tabella, un Model_Abstract
	//Quando dobbiamo prendere una tabella particolare, il metodo prende il parametro, individua il file di definizione della classe e fa una new della classe
	//-> Ritornerà al chiamante la classe che potrà in seguito essere utilizzata
	{
		if (!isset($this->_resources[$name])) { //Non necessariamente l'output viene eseguito da una singola azione
		//ma anche in seguito a più azioni.-> La funzione deve vedere se lo stesso oggetto è stato istanziato
		// o devo procedere con l'istanziazione -> Se quella risorsa già esiste, non eseguirò il codice sottostante ma ritornerò solo il nome della risorsa stessa.
		//Altrimenti prende il contenuto di un array e lo trasforma in una stringa in cui i valori degli elementi dell'array sono separati da un parametro che definisco
            $class = implode('_', array(
                    $this->_getNamespace(), //Estrae il nome che abbiamo associato alla nostra applicazione nell'application.ini
                    'Resource',//Prendiamo la stringa"Application", poi prendiamo la parola Resource e prendiamo il valore di $name, il parametro che abbiamo passato
                    $name)); //-> Application_Resource_$name -> La utilizzo per fare una new -> per istanziare un oggetto di quella classe e ritornarla al chiamante                   
            $this->_resources[$name] = new $class();
        }
	    return $this->_resources[$name]; 
	}
	
	//IMPORTANTE
	//La funzione prende il nome di un modello, crea la stringa e fa una new per istanziare un oggetto a partire da questa classe
	
	//Quando vorremo istanziare il metodo che rappresenta una tabella in particolare, attiveremo il metodo getResource
	//-> Verrà così istanziata la classe 
	
	
	private function _getNamespace() 
    {
        $ns = explode('_', get_class($this)); //la get Namespace è richiamata da getResource 
        //Prende la classe del model istanziato da tale classe astratta -> e fa la explode ritornando il primo elemento
        return $ns[0]; //Ritorniamo il primo elemento dell'array che sarà Application 
    }
	
}
