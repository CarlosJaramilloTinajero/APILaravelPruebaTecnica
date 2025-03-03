## Prueba tecnica API de Productos Back end

Este proyecto de Laravel 12, es una API REST con autenticacion de usuarios, por medio de un login y un registro de ususarios, utilizando el paquete de Laravel Sanctum
para la proteccion de rutas que requierdan de autenticacion de usuario. En este proyecto se genero un CRUD de productos, utilizando una arquitectura en capas, donde se
separan responsabilidades, por controlador, servicios y repositorios, ya que de esta manera la arquitectura del proyecto es mas mantenible y escalable, siguiendo
buenas practicas de desarrollo.

## Pasos de instalacion

- Ejecutar el comando 'composer install' para instalar las depencias del proyecto
- Ejecutar el comando 'cp .env.example .env' para crear una copia del archivo .env.example
- Ejecutar el comando 'php artisan key:generate' para generar la llave de la aplicacion
- Configurar las credenciales de conexion a la base de datos, desde el archivo .env
- Ejecutar el comando 'php artisan migrate' para crear las tablas de la base de datos, ya que se hizo uso de las migraciones de laravel
- Ejecutar el comando 'php artisan serve' para ejecutar el servidor de manera local

## Notas

La subida de archivos de las imagenes de los productos esta funcional para las peticiones a la API, si se desarrollo esa parte desde el Back End, no tuve tiempo de desarrollar esa funcionalidad en el Front End con la App de Vue. 
