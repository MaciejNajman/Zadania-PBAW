<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';
//zaladuj Smarty
require_once _ROOT_PATH.'/lib/smarty/Smarty.class.php';

// W kontrolerze niczego nie wysy³a siê do klienta.
// Wys³aniem odpowiedzi zajmie siê odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametrów
function getParams(&$form){
	$form['kwota'] = isset($_REQUEST['kwota']) ? $_REQUEST['kwota'] : null;
	$form['ile_lat'] = isset($_REQUEST['ile_lat']) ? $_REQUEST['ile_lat'] : null;
	$form['opr'] = isset($_REQUEST['opr']) ? $_REQUEST['opr'] : null;	
}
// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$form,&$infos,&$msgs,&$hide_intro){

	// sprawdzenie, czy parametry zosta³y przekazane - jesli nie to zakoncz walidacje
	if ( ! (isset($form['kwota']) && isset($form['ile_lat']) && isset($form['opr']) ))	return false;
	
	//parametry przekazane zatem
	//nie pokazuj wstêpu strony gdy tryb obliczeñ (aby nie trzeba by³o przesuwaæ)
	// - ta zmienna zostanie u¿yta w widoku aby nie wyœwietlaæ ca³ego bloku itro z t³em
	$hide_intro = true;
	
	$infos [] = 'Przekazano parametry.';

	// sprawdzenie, czy potrzebne wartoœci zosta³y przekazane
	if ( $form['kwota'] == "") $msgs [] = 'Nie podano kwoty kredytu';
	if ( $form['ile_lat'] == "") $msgs [] = 'Nie podano czasu splaty';
	if ( $form['opr'] == "") $msgs [] = 'Nie podano oprocentowania';

	//nie ma sensu walidowaæ dalej gdy brak parametrów
	if ( count($msgs)==0 ) {
		// sprawdzenie, czy $kwota, $ile_lat i $oprocentowanie s¹ liczbami ca³kowitymi
		if (! is_numeric( $form['kwota'] )) $msgs [] = 'Kwota nie jest liczba rzeczywista lub calkowita';
		if (! is_numeric( $form['ile_lat'] )) $msgs [] = 'Czas splaty nie jest liczba calkowita';
		if (! is_numeric( $form['opr'] )) $msgs [] = 'Oprocentowanie nie jest liczba rzeczywista lub calkowita';
	}
	
	if (count($msgs)>0) return false;
	else return true;
}
// 3. wykonaj zadanie jeœli wszystko w porz¹dku

function process(&$form,&$infos,&$msgs,&$result){
	$infos [] = 'Parametry poprawne. Wykonuje obliczenia.';
	
	//zamiana lat na miesi¹ce
	//konwersja parametru ile_lat na int
	$form['ile_lat'] = intval ($form['ile_lat']);
	$form['ile_miesiecy'] = ($form['ile_lat']) * 12;
	
	//konwersja parametru na int
	$form['ile_miesiecy'] = intval($form['ile_miesiecy']);
	//konwersja parametrów na liczbê rzeczywist¹
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

//pozosta³e zmienne niekoniecznie musz¹ istnieæ, dlatego sprawdzamy aby nie otrzymaæ ostrze¿enia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// 5. Wywo³anie szablonu
$smarty->display(_ROOT_PATH.'/app/calc.html');