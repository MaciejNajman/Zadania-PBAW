<?php
require_once 'Config.class.php';

$conf = new Config();

$conf->root_path = dirname(__FILE__);
$conf->server_name = 'localhost:80';
$conf->server_url = 'http://'.$conf->server_name;
$conf->app_root = '/kalk_kredytowy_obiektowosc';
$conf->app_url = $conf->server_url.$conf->app_root;

//gdy korzysta się z bibliotek szablonowania funkcja out(&$param) nie jest już tak potrzebna
?>