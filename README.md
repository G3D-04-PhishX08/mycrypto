# MyCryptoAlert v1.0

Dashboard minimalista que muestra precios de 6 criptomonedas y envía alertas por email cuando se alcanza un precio objetivo.


## Instalación
1. Importa `db/init.sql` en tu MySQL.
2. Ajusta conexión en `save_alert.php` y `cron.php`.
3. Programa el cron para ejecutar `cron.php` cada 5 min:
*/5 * * * * /usr/bin/php /ruta/mycrypto/cron.php >> /dev/null 2>&1

4. ¡Listo!

## Stack
- PHP 8 puro  
- MySQL 1 tabla  
- CoinGecko API (gratis)  
- Cron + mail()  

## Características
- Precios en tiempo real  
- Alertas por email  
- Diseño glassmorphism brutalista