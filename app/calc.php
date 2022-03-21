<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';
//zaladuj Smarty
require_once _ROOT_PATH.'/lib/smarty/Smarty.class.php';

// W kontrolerze niczego nie wysy�a si� do klienta.
// Wys�aniem odpowiedzi zajmie si� odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametr�w
function getParams(&$form){
	$form['kwota'] = isset($_REQUEST['kwota']) ? $_REQUEST['kwota'] : null;
	$form['ile_lat'] = isset($_REQUEST['ile_lat']) ? $_REQUEST['ile_lat'] : null;
	$form['opr'] = isset($_REQUEST['opr']) ? $_REQUEST['opr'] : null;	
}
// 2. walidacja parametr�w z przygotowaniem zmiennych dla widoku
function validate(&$form,&$infos,&$msgs,&$hide_intro){

	// sprawdzenie, czy parametry zosta�y przekazane - jesli nie to zakoncz walidacje
	if ( ! (isset($form['kwota']) && isset($form['ile_lat']) && isset($form['opr']) ))	return false;
	
	//parametry przekazane zatem
	//nie pokazuj wst�pu strony gdy tryb oblicze� (aby nie trzeba by�o przesuwa�)
	// - ta zmienna zostanie u�yta w widoku aby nie wy�wietla� ca�ego bloku itro z t�em
	$hide_intro = true;
	
	$infos [] = 'Przekazano parametry.';

	// sprawdzenie, czy potrzebne warto�ci zosta�y przekazane
	if ( $form['kwota'] == "") $msgs [] = 'Nie podano kwoty kredytu';
	if ( $form['ile_lat'] == "") $msgs [] = 'Nie podano czasu splaty';
	if ( $form['opr'] == "") $msgs [] = 'Nie podano oprocentowania';

	//nie ma sensu walidowa� dalej gdy brak parametr�w
	if ( count($msgs)==0 ) {
		// sprawdzenie, czy $kwota, $ile_lat i $oprocentowanie s� liczbami ca�kowitymi
		if (! is_numeric( $form['kwota'] )) $msgs [] = 'Kwota nie jest liczba rzeczywista lub calkowita';
		if (! is_numeric( $form['ile_lat'] )) $msgs [] = 'Czas splaty nie jest liczba calkowita';
		if (! is_numeric( $form['opr'] )) $msgs [] = 'Oprocentowanie nie jest liczba rzeczywista lub calkowita';
	}
	
	if (count($msgs)>0) return false;
	else return true;
}
// 3. wykonaj zadanie je�li wszystko w porz�dku

function process(&$form,&$infos,&$msgs,&$result){
	$infos [] = 'Parametry poprawne. Wykonuje obliczenia.';
	
	//zamiana lat na miesi�ce
	//konwersja parametru ile_lat na int
	$form['ile_lat'] = intval ($form['ile_lat']);
	$form['ile_miesiecy'] = ($form['ile_lat']) * 12;
	
	//konwersja parametru na int
	$form['ile_miesiecy'] = intval($form['ile_miesiecy']);
	//konwersja parametr�w na liczb� rzeczywist�
	$form['kwota'] = floatval($form['kwota']);
	$form['opr'] = floatval($form['opr']);
	
	
	//wykonanie operacji
	$result = ($form['kwota'] + ($form['kwota'] * ($form['opr']/100))) / $form['ile_miesiecy'];
	$result = round($result,2);
}

//inicjacja zmiennych
$form = null;
$infos = array();
$messages = array();
//$msgs = array();
$result = null;
$hide_intro = false;

getParams($form);
if ( validate($form,$infos,$messages,$hide_intro) ){
	process($form,$infos,$messages,$result);
}

// 4. Przygotowanie danych dla szablonu

$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Kalkulator kredytowy');
$smarty->assign('page_description','Profesjonalne szablonowanie oparte na bibliotece Smarty');
$smarty->assign('page_header','Szablony Smarty');

$smarty->assign('hide_intro',$hide_intro);

//pozosta�e zmienne niekoniecznie musz� istnie�, dlatego sprawdzamy aby nie otrzyma� ostrze�enia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// 5. Wywo�anie szablonu
$smarty->display(_ROOT_PATH.'/app/calc.html');