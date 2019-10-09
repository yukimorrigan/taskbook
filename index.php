<?php 

// FRONT CONTROLLER

// 1. Общие настройки
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. Подключение файлов системы
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Autoload.php');
require_once(ROOT.'/components/RedBean.php');

// 3. Подключение к базе данных
// установить соединение с базой данных
R::setup('mysql:host=127.0.0.1;dbname=taskbook', 'root', '');
// запретить (заморозить) авто-создание полей и таблиц
R::freeze(false);
// проверка подключения
if (!R::testConnection()) {
	exit('Нет подключения к базе данных. Подключите базу данных.');
}

// 4. Вызов Router
$router = new Router();
$router->run();

 ?>