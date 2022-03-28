<?php
// W skrypcie definicji kontrolera nie trzeba do��cza� problematycznego skryptu config.php,
// poniewa� b�dzie on u�yty w miejscach, gdzie config.php zostanie ju� wywo�any.

require_once $conf->root_path.'/lib/smarty/Smarty.class.php';
require_once $conf->root_path.'/lib/Messages.class.php';
require_once $conf->root_path.'/app/calc/CalcForm.class.php';
require_once $conf->root_path.'/app/calc/CalcResult.class.php';

/** Kontroler kalkulatora
 * @author Maciej Najman
 *
 */
class CalcCtrl {

	private $msgs;   //wiadomo�ci dla widoku
	private $infos;  //informacje dla widoku
	private $form;   //dane formularza (do oblicze� i dla widoku)
	private $result; //inne dane dla widoku
	private $hide_intro; //zmienna informuj�ca o tym czy schowa� intro

	/** 
	 * Konstruktor - inicjalizacja w�a�ciwo�ci
	 */
	public function __construct(){
		//stworzenie potrzebnych obiekt�w
		$this->msgs = new Messages();
		$this->form = new CalcForm();
		$this->result = new CalcResult();
		$this->hide_intro = false;
	}
	
	/** 
	 * Pobranie parametr�w
	 */
	public function getParams(){
		$this->form->kwota = isset($_REQUEST ['kwota']) ? $_REQUEST ['kwota'] : null;
		$this->form->ile_lat = isset($_REQUEST ['ile_lat']) ? $_REQUEST ['ile_lat'] : null;
		$this->form->opr = isset($_REQUEST ['opr']) ? $_REQUEST ['opr'] : null;
	}
	
	/** 
	 * Walidacja parametr�w
	 * @return true je�li brak b�ed�w, false w przeciwnym wypadku 
	 */
	public function validate() {
		// sprawdzenie, czy parametry zosta�y przekazane
		if (! (isset ( $this->form->kwota ) && isset ( $this->form->ile_lat ) && isset ( $this->form->opr ))) {
			// sytuacja wyst�pi kiedy np. kontroler zostanie wywo�any bezpo�rednio - nie z formularza
			return false;
		} else { 
			$this->hide_intro = true; //przysz�y pola formularza - schowaj wst�p
		}
		
		// sprawdzenie, czy potrzebne warto�ci zosta�y przekazane
		if ($this->form->kwota == "") {
			$this->msgs->addError('Nie podano kwoty kredytu');
		}
		if ($this->form->ile_lat == "") {
			$this->msgs->addError('Nie podano czasu splaty kredytu');
		}
		if ($this->form->opr == "") {
			$this->msgs->addError('Nie podano oprocentowania');
		}
		
		
		// nie ma sensu walidowa� dalej gdy brak parametr�w
		if (! $this->msgs->isError()) {
			
			// sprawdzenie, czy $kwota, $ile_lat i $opr s� liczbami
			if (! is_numeric ( $this->form->kwota )) {
				$this->msgs->addError('Kwota nie jest liczba rzeczywista lub calkowita');
			}
			
			if (! is_numeric ( $this->form->ile_lat )) {
				$this->msgs->addError('Czas splaty nie jest liczba calkowita');
			}
			if (! is_numeric ( $this->form->opr )) {
				$this->msgs->addError('Oprocentowanie nie jest liczba rzeczywista lub calkowita');
			}
		}
		
		return ! $this->msgs->isError();
	}
	
	/** 
	 * Pobranie warto�ci, walidacja, obliczenie i wy�wietlenie
	 */
	public function process(){

		$this->getparams();
		
		if ($this->validate()) {
				
			//konwersja parametru ile_lat na int
			$this->form->ile_lat = intval ($this->form->ile_lat);
			//zamiana lat na miesi�ce
			$this->form->ile_miesiecy = ($this->form->ile_lat)*12;
			//konwersja parametru ile_miesiecy na int
			$this->form->ile_miesiecy = intval ($this->form->ile_miesiecy);
			
			//konwersja kwoty i oprocentowania na float
			$this->form->kwota = floatval($this->form->kwota);
			$this->form->opr = floatval($this->form->opr);
			
			$this->msgs->addInfo('Parametry poprawne.');
				
			//wykonanie operacji
			$this->result->result = ($this->form->kwota + ($this->form->kwota * ($this->form->opr/100))) / ($this->form->ile_miesiecy);
			//zaokraglenie wyniku do 2 miejsc po przecinku (do groszy)
			$this->result->result = round($this->result->result,2);
			
			$this->msgs->addInfo('Wykonano obliczenia.');
		}
		
		$this->generateView();
	}
	
	
	/**
	 * Wygenerowanie widoku
	 */
	public function generateView(){
		global $conf;
		
		$smarty = new Smarty();
		$smarty->assign('conf',$conf);
		
		$smarty->assign('page_title','Kalkulator kredytowy');
		$smarty->assign('page_description','Kalkulator kredytowy obliczajacy miesieczna rate na podstawie kwoty kredytu, czasu splaty i oprocentowania.');
		$smarty->assign('page_header','Kalkulator w PHP z uzyciem kontrolera glownego');
				
		$smarty->assign('hide_intro',$this->hide_intro);
		
		$smarty->assign('msgs',$this->msgs);
		$smarty->assign('form',$this->form);
		$smarty->assign('res',$this->result);
		
		$smarty->display($conf->root_path.'/app/calc/CalcView.html');
	}
}