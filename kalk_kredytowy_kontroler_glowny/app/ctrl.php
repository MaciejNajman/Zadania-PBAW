<?php
// Skrypt kontrolera g³ównego uruchamiaj¹cy okreœlon¹
// akcjê u¿ytkownika na podstawie przekazanego parametru

//ka¿dy punkt wejœcia aplikacji (skrypt uruchamiany bezpoœrednio przez klienta) musi do³¹czaæ konfiguracjê
require_once dirname (__FILE__).'/../config.php';

//1. pobierz nazwê akcji

$action = $_REQUEST['action'];

//2. wykonanie akcji
switch ($action) {
	default : // 'calcView'
	    // za³aduj definicjê kontrolera
		include_once $conf->root_path.'/app/calc/CalcCtrl.class.php';
		// utwórz obiekt i uzyj
		$ctrl = new CalcCtrl ();
		$ctrl->generateView ();
	break;
	case 'calcCompute' :
		// za³aduj definicjê kontrolera
		include_once $conf->root_path.'/app/calc/CalcCtrl.class.php';
		// utwórz obiekt i uzyj
		$ctrl = new CalcCtrl ();
		$ctrl->process ();
	break;
	case 'action1' :
		// zrób coœ innego ...
		
	break;
	case 'action2' :
		// zrób coœ innego ...
	break;
}