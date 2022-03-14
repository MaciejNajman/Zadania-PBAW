<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysy�a si� do klienta.
// Wys�aniem odpowiedzi zajmie si� odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

//ochrona kontrolera - poni�szy skrypt przerwie przetwarzanie w tym punkcie gdy u�ytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';

// 1. pobranie parametr�w

function getParams(&$kwota,&$ile_lat,&$oprocentowanie){
	$kwota = isset ($_REQUEST ['kwota']) ? $_REQUEST['kwota'] : null;
	$ile_lat = isset($_REQUEST['ile_lat']) ? $_REQUEST['ile_lat'] : null;
	$oprocentowanie = isset($_REQUEST['opr']) ? $_REQUEST['opr'] : null;
}

// 2. walidacja parametr�w z przygotowaniem zmiennych dla widoku

function validate (&$kwota, &$ile_lat, &$oprocentowanie, &$messages){
	// sprawdzenie, czy parametry zosta�y przekazane
	if ( ! (isset($kwota) && isset($ile_lat) && isset($oprocentowanie))) {
		//sytuacja wyst�pi kiedy np. kontroler zostanie wywo�any bezpo�rednio - nie z formularza
		// teraz zak�adamy, ze nie jest to b��d. Po prostu nie wykonamy oblicze�
		return false;
	}

	// sprawdzenie, czy potrzebne warto�ci zosta�y przekazane
	if ( $kwota == "") {
		$messages [] = 'Nie podano kwoty kredytu';
	}
	if ( $ile_lat == "") {
		$messages [] = 'Nie podano czasu splaty';
	}
	if ( $oprocentowanie == "") {
		$messages [] = 'Nie podano oprocentowania';
	}

	//nie ma sensu walidowa� dalej gdy brak parametr�w
	if  (count ( $messages ) != 0) return false;
		
	// sprawdzenie, czy $kwota, $ile_lat i $oprocentowanie s� liczbami calkowitymi
	if (! is_numeric( $kwota )) {
		$messages [] = 'Kwota nie jest liczba rzeczywista';
	}
		
	if (! is_numeric( $ile_lat )) {
			$messages [] = 'Czas splaty nie jest liczba calkowita';
	}
		
	if (! is_numeric( $oprocentowanie )) {
			$messages [] = 'Oprocentowanie nie jest liczba rzeczywista';
	}
	
	if (count ( $messages ) != 0) return false;
	else return true;
}

// 3. wykonaj zadanie je�li wszystko w porz�dku

function process(&$kwota,&$ile_lat,&$oprocentowanie,&$messages,&$result){
	global $role;
	
	//zamiana lat na miesi�ce
	//konwersja parametru ile_lat na int
	$ile_lat = intval ($ile_lat);
	$ile_miesiecy = $ile_lat * 12;
	
	//konwersja parametr�w na int
	$ile_miesiecy = intval($ile_miesiecy);
	//konwersja na liczbe rzeczywista
	$kwota = floatval($kwota);
	$oprocentowanie = floatval ($oprocentowanie);
	
	//wykonanie operacji
	if ($role == 'admin'){
		$result = ($kwota + ($kwota * ($oprocentowanie/100))) / $ile_miesiecy;
		$result = round($result,2);
	} else {
		$messages [] = 'Tylko administrator moze uzywac kalkulatora kredytowego!';
	}
}

//definicja zmiennych kontrolera
$kwota = null;
$ile_lat = null;
$ile_miesiecy = null;
$oprocentowanie = null;
$result = null;
$messages = array();

//pobierz parametry i wykonaj zadanie je�li wszystko w porz�dku
getParams($kwota,$ile_lat,$oprocentowanie);
if ( validate($kwota,$ile_lat,$oprocentowanie,$messages) ) { // gdy brak b��d�w
	process($kwota,$ile_lat,$oprocentowanie,$messages,$result);
}

// 4. Wywo�anie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$kwota,$ile_lat,$oprocentowanie,$result)
//   b�d� dost�pne w do��czonym skrypcie
include 'calc_view.php';