<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysy�a si� do klienta.
// Wys�aniem odpowiedzi zajmie si� odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametr�w

$kwota = $_REQUEST ['kwota'];
$ile_lat = $_REQUEST ['ile_lat'];
$oprocentowanie = $_REQUEST ['opr'];

// 2. walidacja parametr�w z przygotowaniem zmiennych dla widoku

// sprawdzenie, czy parametry zosta�y przekazane
if ( ! (isset($kwota) && isset($ile_lat) && isset($oprocentowanie))) {
	//sytuacja wyst�pi kiedy np. kontroler zostanie wywo�any bezpo�rednio - nie z formularza
	$messages [] = 'Bledne wywolanie aplikacji. Brak jednego z parametrow.';
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
if (empty( $messages )) {
	
	// sprawdzenie, czy $kwota, $ile_lat i $oprocentowanie s� liczbami ca�kowitymi
	if (! is_numeric( $kwota )) {
		$messages [] = 'Kwota nie jest liczba rzeczywista';
	}
	
	if (! is_numeric( $ile_lat )) {
		$messages [] = 'Czas splaty nie jest liczba calkowita';
	}
	
	if (! is_numeric( $oprocentowanie )) {
		$messages [] = 'Oprocentowanie nie jest liczba rzeczywista';
	}	

}

// 3. wykonaj zadanie je�li wszystko w porz�dku

if (empty ( $messages )) { // gdy brak b��d�w
	
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
	$result = ($kwota + ($kwota * ($oprocentowanie/100))) / $ile_miesiecy;
	$result = round($result,2);
	
}

// 4. Wywo�anie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$kwota,$ile_lat,$oprocentowanie,$result)
//   b�d� dost�pne w do��czonym skrypcie
include 'calc_view.php';