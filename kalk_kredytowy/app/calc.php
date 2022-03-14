<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysy³a siê do klienta.
// Wys³aniem odpowiedzi zajmie siê odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametrów

$kwota = $_REQUEST ['kwota'];
$ile_lat = $_REQUEST ['ile_lat'];
$oprocentowanie = $_REQUEST ['opr'];

// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku

// sprawdzenie, czy parametry zosta³y przekazane
if ( ! (isset($kwota) && isset($ile_lat) && isset($oprocentowanie))) {
	//sytuacja wyst¹pi kiedy np. kontroler zostanie wywo³any bezpoœrednio - nie z formularza
	$messages [] = 'Bledne wywolanie aplikacji. Brak jednego z parametrow.';
}

// sprawdzenie, czy potrzebne wartoœci zosta³y przekazane
if ( $kwota == "") {
	$messages [] = 'Nie podano kwoty kredytu';
}
if ( $ile_lat == "") {
	$messages [] = 'Nie podano czasu splaty';
}
if ( $oprocentowanie == "") {
	$messages [] = 'Nie podano oprocentowania';
}

//nie ma sensu walidowaæ dalej gdy brak parametrów
if (empty( $messages )) {
	
	// sprawdzenie, czy $kwota, $ile_lat i $oprocentowanie s¹ liczbami ca³kowitymi
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

// 3. wykonaj zadanie jeœli wszystko w porz¹dku

if (empty ( $messages )) { // gdy brak b³êdów
	
	//zamiana lat na miesi¹ce
	
	//konwersja parametru ile_lat na int
	$ile_lat = intval ($ile_lat);
	$ile_miesiecy = $ile_lat * 12;
	
	//konwersja parametrów na int
	$ile_miesiecy = intval($ile_miesiecy);
	//konwersja na liczbe rzeczywista
	$kwota = floatval($kwota);
	$oprocentowanie = floatval ($oprocentowanie);
	
	
	//wykonanie operacji
	$result = ($kwota + ($kwota * ($oprocentowanie/100))) / $ile_miesiecy;
	$result = round($result,2);
	
}

// 4. Wywo³anie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$kwota,$ile_lat,$oprocentowanie,$result)
//   bêd¹ dostêpne w do³¹czonym skrypcie
include 'calc_view.php';