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

//обращение к модели данных
if(isset($_GET['client']))
{
	$data = $controller->report($_GET['client']);
	$client = $clients[$_GET['client']];
}
else
{
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


