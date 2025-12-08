Este repositorio contiene la estructura de una pagina llamada "jueguin plataformero con un sistema de LOGIN implementado con PHP y MYSQL
ES NECESARIO replicar el entorno del servidor para hacerlo funcionar

Requisitos y Herramientas Necesarias
Para correr este proyecto, necesitas un entorno de servidor local que soporte PHP y MySQL.

Servidor Local: Instalar XAMPP (recomendado para Windows) o WAMP/MAMP.

Servicios Activos: Asegurarse de que los servicios de Apache y MySQL estén corriendo

1. Ubicación de Archivos
Descarga o clona este repositorio de GitHub en tu máquina.

Mueve toda la carpeta del proyecto al directorio raíz de tu servidor:

XAMPP (Windows): C:\xampp\htdocs\

MAMP (Mac/Windows): C:\MAMP\htdocs\

2. Configuración de la Base de Datos
El sistema necesita una base de datos para almacenar los usuarios.

Abre tu navegador y accede a phpMyAdmin (generalmente en http://localhost/phpmyadmin/).

Crea una nueva base de datos llamada mosa_db.

Importa la estructura: Usa la herramienta de "Importar" de phpMyAdmin para cargar el archivo mosa.sql incluido en este repositorio. Esto creará automáticamente la tabla usuarios.

Accede a la URL de tu proyecto: http://localhost/mosa/index.php
eso hara que puedas usar la pagina
