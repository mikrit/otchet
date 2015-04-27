<?php

define('EXT', '.php');

define("HOME_DIR", dirname(__FILE__));

require_once('lib/loader.php');
spl_autoload_register(array('Autoloader', 'auto_load'));
date_default_timezone_set('Europe/Moscow');


//Вызов контроллера по запросу или по умолчанию
$controller = new Controller();

Lib_Config::get_instance();

$clients = array
(
	'avilon' => 'Авилон',
	'indep' => 'Независимость',
	'nep' => 'НЭП',
	'rolf' => 'Рольф',
	'avinon' => 'Авиньон',
	'ast' => 'АСТ',
	'evrogarant' => 'Еврогарант',
	'sviaznoy' => 'Связной',
);

$config_clients = Lib_Config::get_config('clients');

//обращение к модели данных
if(isset($_GET['client']))
{
	if(!isset($config_clients[$_GET['client']]))
	{
		die("No config ".$_GET['client']);
	}

	$reliz = $config_clients[$_GET['client']]['reliz'];
	$analitic = $config_clients[$_GET['client']]['analitic'];

	$data = $controller->report($_GET['client']);
	$client = $clients[$_GET['client']];
}
else
{
	$first_key = array_search(current($clients), $clients);

	if(!isset($config_clients[$first_key]))
	{
		die("No config ".$first_key);
	}

	$reliz = $config_clients[$first_key]['reliz'];
	$analitic = $config_clients[$first_key]['analitic'];

	$first_elem = current($clients);
	$data = $controller->report(array_search($first_elem, $clients));
	$client = $first_elem;
}



$view_menu = View::factory('menu');
$view_menu->clients = $clients;
$view_menu->client_current = $client;
$menu = $view_menu->render();

$content_menu = View::factory('content');
$content_menu->data = $data;
$content_menu->client = $client;
$content = $content_menu->render();


require_once('views/template.php');

//Вызов шаблона


