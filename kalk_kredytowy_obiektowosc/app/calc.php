<?php
//Skrypt uruchamiaj�cy akcj� wykonania oblicze� kalkulatora
// - nale�y zwr�ci� uwag� jak znacz�co jego rola uleg�a zmianie
//   po wstawieniu funkcjonalno�ci do klasy kontrolera

require_once dirname(__FILE__).'/../config.php';

//za�aduj kontroler
require_once $conf->root_path.'/app/CalcCtrl.class.php';

//utw�rz obiekt i u�yj
$ctrl = new CalcCtrl();
$ctrl->process();