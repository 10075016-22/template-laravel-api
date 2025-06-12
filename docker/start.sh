#!/bin/bash

# Obtener la IP del contenedor de MySQL
MYSQL_IP=$(getent hosts laravel_db | awk '{ print $1 }')
echo "IP de MySQL: $MYSQL_IP"

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Actualizar información de conexión a la base de datos en .env
sed -i "s/DB_HOST=.*/DB_HOST=$MYSQL_IP/" .env
sed -i 's/DB_PORT=.*/DB_PORT=3306/' .env
sed -i 's/DB_DATABASE=.*/DB_DATABASE=laravel/' .env
sed -i 's/DB_USERNAME=.*/DB_USERNAME=laravel_user/' .env
sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=laravel_password/' .env

# Esperar a que MySQL esté disponible
echo "Esperando a que MySQL esté disponible..."
until nc -z -v -w30 $MYSQL_IP 3306
do
  echo "Esperando a que MySQL se inicie..."
  sleep 5
done
echo "¡MySQL está disponible! Continuando..."

# Instalar dependencias
composer install --no-interaction --optimize-autoloader --no-dev

# Generar clave de aplicación si no está configurada
php artisan key:generate --no-interaction --force
php artisan jwt:secret --no-interaction --force

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed --no-interaction --force

# Create storage link
php artisan storage:link

# Corregir permisos
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html/storage

# Iniciar Supervisor para gestionar los workers de cola
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

# Iniciar Apache
apache2-foreground
