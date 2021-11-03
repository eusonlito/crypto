# Requisitos

* PHP 8.0
* Apache 2.4.51 +-
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
nohup php artisan exchange:sync:socket --platform_id=1 > storage/logs/exchange-sync-socket.log 2>&1 &
nohup php artisan exchange:ticker:socket --platform_id=2 > storage/logs/exchange-ticker-socket.log 2>&1 &
```

# Actualización

```bash
composer deploy
```

Listo!