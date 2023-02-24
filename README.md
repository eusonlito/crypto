# Requisitos

* PHP >= 8.1
* Apache/nginx
* MySQL 8.0
* Composer 2

# Instalación

```bash
# Creamos la base de datos
mysql -e 'CREATE DATABASE `crypto` character set UTF8mb4 collate utf8mb4_bin;'

# Clonamos el repositorio
git clone https://github.com/eusonlito/crypto.git

cd crypto

# Duplicamos la configuración genérica como .env
cp .env.example .env

# Editamos los datos necesarios para la instalación local
vi .env

# Instalamos las dependencias
composer install --no-scripts --no-dev && composer install --no-dev

# Generamos la clave de cifrado
php artisan key:generate

# Lanzamos las migraciones
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\Database

# Desplegamos
composer artisan-cache
````

# Desactivación de plataformas

El sistema genera unos 18 millones de valores de cambio cada 15 días, con lo cual necesitarás una buena máquina para soportar eso.

Si deseas desactivar cualquier plataforma puedes hacerlo indicando el campo `enabled` a `false` de la tabla `platform`, tu servidor te lo agradecerá.

# Carga de datos inicial

```bash
php artisan currency:sync:all
php artisan product:sync:all
php artisan product:fiat:all
php artisan exchange:sync:all
```

# Procesos en background

```bash
nohup php artisan schedule:work > storage/logs/schedule-work.log 2>&1 &

# Binance
nohup php artisan exchange:sync:socket --platform_id=1 > storage/logs/exchange-sync-socket-1.log 2>&1 &

# Coinbase PRO
nohup php artisan exchange:ticker:socket --platform_id=2 > storage/logs/exchange-ticker-socket-2.log 2>&1 &

# KuCoin
nohup php artisan exchange:ticker:socket --platform_id=3 > storage/logs/exchange-ticker-socket-3.log 2>&1 &
```

# Actualización

```bash
composer deploy
```

# Capturas

![crypto-04](https://user-images.githubusercontent.com/644551/149986612-6303a6fd-9aec-426e-8499-37a0e7e011fe.png)
![crypto-03](https://user-images.githubusercontent.com/644551/149986613-61e64584-609b-4d81-afad-9445fabfe28a.png)
![crypto-02](https://user-images.githubusercontent.com/644551/149986615-5170bac2-4e3b-4ac3-b044-f88eb185f1a3.png)
![crypto-01](https://user-images.githubusercontent.com/644551/149986618-bd4927bd-ae63-45fd-917e-4e79c3532a5b.png)

Listo!
