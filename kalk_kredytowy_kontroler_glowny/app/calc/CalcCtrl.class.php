<?php
// W skrypcie definicji kontrolera nie trzeba do³¹czaæ problematycznego skryptu config.php,
// poniewa¿ bêdzie on u¿yty w miejscach, gdzie config.php zostanie ju¿ wywo³any.

require_once $conf->root_path.'/lib/smarty/Smarty.class.php';
require_once $conf->root_path.'/lib/Messages.class.php';
require_once $conf->root_path.'/app/calc/CalcForm.class.php';
require_once $conf->root_path.'/app/calc/CalcResult.class.php';

/** Kontroler kalkulatora
 * @author Maciej Najman
 *
 */
class CalcCtrl {

	private $msgs;   //wiadomoœci dla widoku
	private $infos;  //informacje dla widoku
	private $form;   //dane formularza (do obliczeñ i dla widoku)
	private $result; //inne dane dla widoku
	private $hide_intro; //zmienna informuj¹ca o tym czy schowaæ intro

	/** 
	 * Konstruktor - inicjalizacja w³aœciwoœci
	 */
	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->msgs = new Messages();
		$this->form = new CalcForm();
		$this->result = new CalcResult();
		$this->hide_intro = false;
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
		$this->form->kwota = isset($_REQUEST ['kwota']) ? $_REQUEST ['kwota'] : null;
		$this->form->ile_lat = isset($_REQUEST ['ile_lat']) ? $_REQUEST ['ile_lat'] : null;
		$this->form->opr = isset($_REQUEST ['opr']) ? $_REQUEST ['opr'] : null;
	}
	
	/** 
	 * Walidacja parametrów
	 * @return true jeœli brak b³edów, false w przeciwnym wypadku 
	 */
	public function validate() {
		// sprawdzenie, czy parametry zosta³y przekazane
		if (! (isset ( $this->form->kwota ) && isset ( $this->form->ile_lat ) && isset ( $this->form->opr ))) {
			// sytuacja wyst¹pi kiedy np. kontroler zostanie wywo³any bezpoœrednio - nie z formularza
			return false;
		} else { 
			$this->hide_intro = true; //przysz³y pola formularza - schowaj wstêp
		}
		
		// sprawdzenie, czy potrzebne wartoœci zosta³y przekazane
		if ($this->form->kwota == "") {
			$this->msgs->addError('Nie podano kwoty kredytu');
		}
		if ($this->form->ile_lat == "") {
			$this->msgs->addError('Nie podano czasu splaty kredytu');
		}
		if ($this->form->opr == "") {
			$this->msgs->addError('Nie podano oprocentowania');
		}
		
		
		// nie ma sensu walidowaæ dalej gdy brak parametrów
		if (! $this->msgs->isError()) {
			
			// sprawdzenie, czy $kwota, $ile_lat i $opr s¹ liczbami
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
	 * Pobranie wartoœci, walidacja, obliczenie i wyœwietlenie
	 */
	public function process(){

		$this->getparams();
		
		if ($this->validate()) {
				
			//konwersja parametru ile_lat na int
			$this->form->ile_lat = intval ($this->form->ile_lat);
			//zamiana lat na miesi¹ce
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