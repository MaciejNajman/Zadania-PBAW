<?php
require_once dirname(__FILE__).'/config.php';

//przekierowanie przegl�darki klienta (redirect)
//header("Location: ".$conf->root_path."/app/calc.php");

//przekazanie ��dania do nast�pnego dokumentu ("forward")
include $conf->root_path.'/app/ctrl.php';