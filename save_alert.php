<?php
// Configuración de conexión
$host = 'localhost';
$db   = 'mycrypto';
$user = 'root';       
$pass = '';           
$charset = 'utf8mb4';

// DSN y opciones PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Recibir y validar datos
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $symbol = trim($_POST['symbol'] ?? '');
    $target_price = filter_input(INPUT_POST, 'target_price', FILTER_VALIDATE_FLOAT);
    $direction = in_array($_POST['direction'], ['up','down']) ? $_POST['direction'] : 'up';

    if (!$email || !$symbol || $target_price === false) {
        die('Datos inválidos.');
    }

    // Insertar
    $stmt = $pdo->prepare(
        "INSERT INTO alerts (email, symbol, target_price, direction)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$email, $symbol, $target_price, $direction]);

    // Redirigir con mensaje 
    header('Location: index.php?ok=1');
} catch (PDOException $e) {
    // Log interno y mensaje genérico
    error_log($e->getMessage());
    die('Error al guardar.');
}