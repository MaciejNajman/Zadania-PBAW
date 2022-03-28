<?php
// Skrypt kontrolera g��wnego uruchamiaj�cy okre�lon�
// akcj� u�ytkownika na podstawie przekazanego parametru

//ka�dy punkt wej�cia aplikacji (skrypt uruchamiany bezpo�rednio przez klienta) musi do��cza� konfiguracj�
require_once dirname (__FILE__).'/../config.php';

//1. pobierz nazw� akcji

$action = $_REQUEST['action'];

//2. wykonanie akcji
switch ($action) {
	default : // 'calcView'
	    // za�aduj definicj� kontrolera
		include_once $conf->root_path.'/app/calc/CalcCtrl.class.php';
		// utw�rz obiekt i uzyj
		$ctrl = new CalcCtrl ();
		$ctrl->generateView ();
	break;
	case 'calcCompute' :
		// za�aduj definicj� kontrolera
		include_once $conf->root_path.'/app/calc/CalcCtrl.class.php';
		// utw�rz obiekt i uzyj
		$ctrl = new CalcCtrl ();
		$ctrl->process ();
	break;
	case 'action1' :
		// zr�b co� innego ...
		
	break;
	case 'action2' :
		// zr�b co� innego ...
	break;
}