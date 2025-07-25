<?php
// 1) Configuración
$host = 'localhost';
$db   = 'mycrypto';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit(1);
}

// Obtener alertas activas
$stmt = $pdo->query("SELECT * FROM alerts");
$alerts = $stmt->fetchAll();

if (!$alerts) {
    exit("Sin alertas.\n");
}

// Obtener precios (mismo endpoint que index.php)
$symbols = array_unique(array_column($alerts, 'symbol'));
$ids = implode(',', $symbols);
$url = "https://api.coingecko.com/api/v3/simple/price?ids=$ids&vs_currencies=usd";
$prices = json_decode(file_get_contents($url), true);

// Procesar y enviar
foreach ($alerts as $alert) {
    $symbol = $alert['symbol'];
    if (!isset($prices[$symbol]['usd'])) continue;

    $current = (float) $prices[$symbol]['usd'];
    $target  = (float) $alert['target_price'];
    $shouldSend = false;

    if ($alert['direction'] === 'up' && $current >= $target) {
        $shouldSend = true;
    } elseif ($alert['direction'] === 'down' && $current <= $target) {
        $shouldSend = true;
    }

    if ($shouldSend) {
        $subject = "MyCryptoAlert: $symbol";
        $body    = "¡Alerta activada!\n\n"
                 . ucfirst($symbol) . " ahora vale $" . number_format($current, 2) . "\n"
                 . "Objetivo: $target ({$alert['direction']})\n\n"
                 . "— MyCryptoAlert";

        mail($alert['email'], $subject, $body, "From: noreply@mycrypto.local\r\n");

        // Opción 1: borrar alerta
        $pdo->prepare("DELETE FROM alerts WHERE id = ?")->execute([$alert['id']]);

    }
}

echo "Cron ejecutado.\n";
?>