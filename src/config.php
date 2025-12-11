<?php

require_once "../vendor/autoload.php";

Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'])->load();

// Configurazione Database da .env
$host = $_SERVER['DB_HOST'];
$dbname = $_SERVER['DB_NAME'];
$user = $_SERVER['DB_USER'];
$pass = $_SERVER['DB_PASS'];
$dbPort = $_SERVER['DB_PORT'];

try {
    $pdo = new PDO("mysql:host=$host;port=$dbPort;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore connessione DB: " . $e->getMessage());
}

// Avvia la sessione solo se non è già attiva
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}