<?php
// Precios en vivo (CoinGecko sin key)
$endpoint = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,binancecoin,cardano,solana,polkadot&vs_currencies=usd&include_24h_change=true';
$prices = json_decode(file_get_contents($endpoint), true) ?? [];
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>MyCryptoAlert</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f0f0f;
            --glass: rgba(255, 255, 255, .08);
            --accent: #6366f1;
            --text: #e5e5e5
        }

        body {
            margin: 0;
            font-family: Inter, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh
        }

        .glass {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 24px;
            padding: 2.5rem 3rem;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3)
        }

        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 700
        }

        .price {
            font-size: 1.1rem;
            margin: .25rem 0
        }

        form {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: .75rem
        }

        input,
        select {
            padding: .75rem 1rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, .2);
            background: rgba(255, 255, 255, .05);
            color: #fff;
            outline: none
        }

        button {
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            border: none;
            color: #fff;
            padding: .75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer
        }

        button:hover {
            filter: brightness(1.1)
        }
    </style>
</head>

<body>
    <div class="glass">
        <h1>MyCryptoAlert</h1>

        <!-- Precios -->
        <?php foreach ($prices as $coin => $data): ?>
            <div class="price">
                <strong><?= ucfirst($coin) ?>:</strong> $<?= number_format($data['usd'], 2) ?>
                <?php
                $change = $data['usd_24h_change'] ?? 0;
                $color  = $change > 0 ? '#4ade80' : '#f87171';
                ?>
                <small style="color:<?= $color ?>">
                    (<?= number_format($change, 2) ?> %)
                </small>
            </div>
        <?php endforeach; ?>

        <!-- Formulario alertas -->
        <form method="post" action="save_alert.php">
            <input type="email" name="email" placeholder="Tu email" required>
            <select name="symbol" required>
                <?php foreach ($prices as $c => $d): ?>
                    <option value="<?= $c ?>"><?= ucfirst($c) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" step="0.01" name="target_price" placeholder="Precio objetivo USD" required>
            <select name="direction" required>
                <option value="up">Sube a</option>
                <option value="down">Baja a</option>
            </select>
            <button type="submit">Crear alerta</button>
        </form>
    </div>
</body>

</html>