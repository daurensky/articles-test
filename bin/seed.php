<?php

define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Infrastructure\Seeder;

$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s',
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_DATABASE']
);
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Запуск сидинга...\n";
    $seeder = new Seeder($pdo);
    $seeder->run();
    echo "База данных успешно наполнена!\n";
} catch (\Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}