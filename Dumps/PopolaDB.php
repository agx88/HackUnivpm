<?php

$HOST	= "localhost";
$DB		= "cleanbin";
$USER	= "root";
$PSWD	= "";


 
$conn = new mysqli($HOST, $USER, $PSWD, $DB) or die('Error connecting to server or selecting DB');

// Carica il DUMP vuoto.
$command = "DROP DATABASE $DB";
$conn->query($command) or die("Errore nella cancellazione del DB");
$command = "CREATE DATABASE $DB";
$conn->query($command) or die("Errore nella creazione del DB");


$command = "/opt/lampp/bin/mysql --user=$USER --password=$PSWD --host=$HOST $DB < /opt/lampp/htdocs/HackUnivpm/Dumps/cleanbin_24_1736.sql";
system($command);

$conn = new mysqli($HOST, $USER, $PSWD, $DB) or die('Error connecting to server or selecting DB');


//*

// ------------------- Utenti
// Inserisco 1 Admin, 2 operatori e 3 netturbini
// Admin
$i = 0;
$query = "INSERT INTO `utenti` (`Id`, `Nome`, `Cognome`, `Role`, `Password`) VALUES (NULL, 'Admin_$i', 'Admin_$i', 'Admin', '1234');";

if($conn->query($query) === TRUE){
	echo "<H4>Inserimento in utenti di (Admin) Admin_$i è avvenuta correttamente in 'utenti'</H4>";
}else{
	echo "Problema di inserimento in 'utenti' per gli Admin.";
}

// Operatori
for($i=1; $i<= 2; $i++){
	$query = "INSERT INTO `utenti` (`Id`, `Nome`, `Cognome`, `Role`, `Password`) VALUES (NULL, 'Pippo_$i', 'Paperino_$i', 'Operatore', '1234');";

	if($conn->query($query) === TRUE){
		echo "<H4>Inserimento in utenti di (Operatore) Pippo_$i è avvenuta correttamente in 'utenti'</H4>";
	}else{
		echo "Problema di inserimento in 'utenti' per gli operatori.";
	}
}
// Netturbini
for($i=1; $i<= 3; $i++){
	$query = "INSERT INTO `utenti` (`Id`, `Nome`, `Cognome`, `Role`, `Password`) VALUES (NULL, 'Topolino_$i', 'Pluto_$i', 'Netturbino', '1234');";

	if($conn->query($query) === TRUE){
		echo "<H4>Inserimento in utenti di (Netturbino) Topolino_$i è avvenuta correttamente in 'utenti'</H4>";
	}
	else{
		echo "Problema di inserimento in 'utenti' per i netturbini.";
	}
}



// ------------------------ Tipologie
$query = "INSERT INTO `tipologie` (`Id`, `Tipo`) VALUES (NULL, 'Indifferenziata'), (NULL, 'Carta'), (NULL, 'Plastica-Vetro');";

if($conn->query($query) === TRUE){
	echo "<H4>Inserimento in 'tipologie' è avvenuta correttamente</H4>";
}
else{
	echo "Problema di inserimento in 'tipologie'.";
}


// ------------------------ Camion
// Inserisco 2 camion
for($i=1; $i<= 2; $i++){
	$cap = 2500 * $i;
	$ser = 100 * $i;
	$tipo = $i;
	$query = "INSERT INTO `camion` (`Id`, `Peso_max`, `Peso_attuale`, `Serbatoio`, `Tipologia_id`) VALUES (NULL, '$cap', '0', '$ser', '$i');";

	if($conn->query($query) === TRUE){
		echo "<H4>Inserimento in camion di $i è avvenuta correttamente</H4>";
	}
	else{
		echo "Problema di inserimento in 'camion'.";
	}
}



// --------------------------- Clusters
// Inserisco 5 clusters
for($i=1; $i<= 5; $i++){
	$query = "INSERT INTO `clusters` (`Id`, `Latitudine`, `Longitudine`, `Via`, `NroCivico`) VALUES (NULL, '0', '0', 'Piazza Cavour', '$i');";
	
	
	if($conn->query($query) === TRUE){
		echo "<H4>Inserimento in 'clusters' di $i è avvenuta correttamente</H4>";
	}
	else{
		echo "Problema di inserimento in 'clusters'.";
	}
}


// --------------------------- Cassonetti
// Inserisco 15 cassonetti
for($i=1; $i<= 15; $i++){
	$tipo = ($i % 3) + 1;
	$cluster = ($i % 5) + 1;	
	
	$query = "INSERT INTO `cassonetti` (`Id`, `Peso_max`, `Peso_attuale`, `Volume`, `Colore`, `Tipologia_id`, `Clusters_id`, `Priorita`, `DaSostituire`) VALUES (NULL, '100', '0', '10', '', '$tipo', '$cluster', '1', '0');";
	
	
	if($conn->query($query) === TRUE){
		echo "<H4>Inserimento in 'cassonetti' di $i è avvenuta correttamente</H4>";
	}
	else{
		echo "Problema di inserimento in 'cassonetti'.";
	}
}



// --------------------------- Percorsi
// Inserisco 2 percorsi
// 1° Percorso	
$query = "INSERT INTO `percorso` (`Id`, `Data`) VALUES (1, '2016-11-24');";


if($conn->query($query) === TRUE){
	echo "<H4>Inserimento in 'percorso' di '1' è avvenuta correttamente</H4>";
}
else{
	echo "Problema di inserimento in 'percorsi'.";
}

// 2° Percorso	
$query = "INSERT INTO `percorso` (`Id`, `Data`) VALUES (2, '2016-11-23')";


if($conn->query($query) === TRUE){
	echo "<H4>Inserimento in 'percorso' di '2' è avvenuta correttamente</H4>";
}
else{
	echo "Problema di inserimento in 'percorsi'.";
}

// --------------- nodiPercorsi

// 1° Percorso	
$query = "INSERT INTO `nodiPercorsi` (`Id`, `Cluster_id`) VALUES ('1', '1'), ('1', '3');";


if($conn->query($query) === TRUE){
	echo "<H4>Inserimento in 'nodiPercorsi' di '1' è avvenuta correttamente</H4>";
}
else{
	echo "Problema di inserimento in 'percorsi'.";
}

// 2° Percorso	
$query = "INSERT INTO `nodiPercorsi` (`Id`, `Cluster_id`) VALUES ('2', '2'), ('2', '4'), ('2', '5');";


if($conn->query($query) === TRUE){
	echo "<H4>Inserimento in 'nodiPercorsi' di '2' è avvenuta correttamente</H4>";
}
else{
	echo "Problema di inserimento in 'nodiPercorsi'.";
}

// ---------------------- Turni
$query = "INSERT INTO `turni` (`Utente_id`, `Percorso_id`, `Camion_id`) VALUES ('1', '1', '1'), ('2', '1', '1'), ('3', '1', '1'), ('4', '2', '2'), ('5', '2', '2'), ('6', '2', '2');";


if($conn->query($query) === TRUE){
	echo "<H4>Inserimento in 'turni' è avvenuta correttamente</H4>";
}
else{
	echo "Problema di inserimento in 'turni'.";
}


//*/
