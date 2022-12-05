<?php

require('pdo/pdo_connect.php');
require('pdo/database_handler.php');
require __DIR__ . '\vendor\autoload.php';
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

// Получение данных из тела запроса
function getFormData($method) {

    // GET или POST: данные возвращаем как есть (FORM DATA)
    if ($method === 'GET') return $_GET;
    if ($method === 'POST') return $_POST;

    // PUT, PATCH или DELETE (JSON RAW)

    return json_decode(file_get_contents('php://input'), true);
}

// Определяем метод запроса
$method = $_SERVER['REQUEST_METHOD'];


// Получаем данные из тела запроса
$formData = getFormData($method);

// Разбираем url
$url = (isset($_GET['q'])) ? $_GET['q'] : '';
$url = rtrim($url, '/');
$urls = explode('/', $url);


// Определяем роутер и url data
$controller = $urls[0];
$urlData = array_slice($urls, 1);

// Подключаем файл-роутер и запускаем главную функцию
include_once 'controllers/' . $controller . '.php';
route($method, $urlData, $formData);








