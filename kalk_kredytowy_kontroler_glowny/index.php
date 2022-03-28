<?php
require_once dirname(__FILE__).'/config.php';

//przekierowanie przegl¹darki klienta (redirect)
//header("Location: ".$conf->root_path."/app/calc.php");

//przekazanie ¿¹dania do nastêpnego dokumentu ("forward")
include $conf->root_path.'/app/ctrl.php';