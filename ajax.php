<?php
error_reporting(E_ALL && E_NOTICE);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

session_start();

if (empty($_POST['_token']) || !hash_equals($_SESSION['token'], $_POST['_token'])) {
    print (json_encode([
        'success'       => false,
        'error_message' => "CSRF Token Failed!"
    ]));
    die;
}

$config = parse_ini_file(".env");

include_once __DIR__ . "/classes/db.php";
$db = new db($config['DB_HOST'], $config['DB_NAME'], $config['DB_USER'], $config['DB_PASS']);

include_once __DIR__ . "/classes/pages.php";
$pages = new pages();
$pages->setDB($db);
$pages->setConfig($config);
$pages->router('list');
die;
