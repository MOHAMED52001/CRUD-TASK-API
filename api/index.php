<?php
require dirname(__DIR__)."/vendor/autoload.php";
$env = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$env->load();
$path = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);

$parts = explode("/",$path);

$method = $_SERVER['REQUEST_METHOD'];
$resource = $parts[3];
$id = $parts[4] ?? null;

if($resource !== "tasks"){
    http_response_code(404);
    exit;
}


$database = new Database($_ENV['DB_HOST'],$_ENV['DB_NAME'],$_ENV['DB_USER'],$_ENV['DB_PASSWD']);
$taskGateway = new TaskGateway($database);

$controller = new TaskController($taskGateway);
$controller->proccess_Request($method,$id);




?>   