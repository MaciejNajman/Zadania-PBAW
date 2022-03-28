<?php
//Skrypt uruchamiaj¹cy akcjê wykonania obliczeñ kalkulatora
// - nale¿y zwróciæ uwagê jak znacz¹co jego rola uleg³a zmianie
//   po wstawieniu funkcjonalnoœci do klasy kontrolera

require_once dirname(__FILE__).'/../config.php';

//za³aduj kontroler
require_once $conf->root_path.'/app/CalcCtrl.class.php';

//utwórz obiekt i u¿yj
$ctrl = new CalcCtrl();
$ctrl->process();