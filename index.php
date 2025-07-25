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
            --text: #e5e5e5;
            --text-secondary: #a1a1aa;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(-45deg, #0f0f0f, #1a1a2e, #16213e, #0f3460);
            background-size: 400% 400%;
            animation: bg 15s ease infinite;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes bg {
            0% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }

            100% {
                background-position: 0% 50%
            }
        }

        .glass {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3);
        }

        h1 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem
        }

        .price {
            display: flex;
            justify-content: space-between;
            margin: .5rem 0;
            font-size: 1.1rem;
            letter-spacing: .5px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: .75rem;
            margin-top: 2rem
        }

        input,
        select {
            padding: .75rem 1rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, .2);
            background: rgba(255, 255, 255, .05);
            color: var(--text);
            outline: none
        }

        input::placeholder {
            color: var(--text-secondary)
        }

        button {
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            border: none;
            color: #fff;
            padding: .75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all .3s;
            box-shadow: 0 4px 15px rgba(99, 102, 241, .4);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(99, 102, 241, .6)
        }
    </style>
</head>

<body>
    <div class="glass">
        <h1>MyCryptoAlert</h1>

        <!-- Precios -->
        <?php foreach ($prices as $coin => $data): ?>
            <?php $change = $data['usd_24h_change'] ?? 0; ?>
            <div class="price">
                <span><?= ucfirst($coin) ?></span>
                <span>
                    $<?= number_format($data['usd'], 2) ?>
                    <small style="color:<?= $change > 0 ? '#4ade80' : '#f87171' ?>">
                        <?= $change > 0 ? '+' : '' ?><?= number_format($change, 2) ?> %
                    </small>
                </span>
            </div>
        <?php endforeach; ?>

        <!-- Formulario (sin cambios) -->
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