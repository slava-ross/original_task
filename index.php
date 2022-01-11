<?php
/**
*   -A-     Автор - Ягодаров Ярослав Владимирович
*   -D-     WEB-приложение "Картотека книг"
*   -Date-  11.01.2022
*/

//error_reporting(E_ALL && E_NOTICE);
//ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
session_start();

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = md5(uniqid(rand(), TRUE));
}

$config = parse_ini_file(".env");

/**
 * -D- Подключение к базе данных
 */
include_once __DIR__ . "/classes/db.php";
$db = new db($config['DB_HOST'],$config['DB_NAME'],$config['DB_USER'],$config['DB_PASS']);

/**
 * -D- Запуск Page-контроллера
 */
include_once __DIR__ . "/classes/pages.php";
$pages = new pages();
$pages->setDB($db);
$pages->setConfig($config);

if (!isset($_GET['page'])) {
    $_GET['page'] = NULL;
}
$pages->router($_GET['page']);
