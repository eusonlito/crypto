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

# Capturas

![crypto-1](https://user-images.githubusercontent.com/644551/140419472-b1da492f-2cbc-4a29-83b8-c194594ba9dc.png)
![crypto-2](https://user-images.githubusercontent.com/644551/140419495-575e4b66-8704-4111-8886-8b062453c04c.png)
![crypto-3](https://user-images.githubusercontent.com/644551/140419508-7632c08d-ef85-4dc7-a719-2b9eccab8e4e.png)
![crypto-4](https://user-images.githubusercontent.com/644551/140419549-9952fb2e-f07c-4ece-bbb4-7d27c37ed23c.png)
![crypto-5](https://user-images.githubusercontent.com/644551/140419926-651c16c1-bc2f-4c14-987b-d829d3cce79a.png)
![crypto-6](https://user-images.githubusercontent.com/644551/140419742-feb5bb67-29d9-4ff0-bc34-aed2bf98b063.png)

Listo!
