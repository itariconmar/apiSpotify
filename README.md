# apiSpotify

## ¿Cómo montar el proyecto en local?

1. Clonar el repositorio
2. Correr el comando: composer install
3. Crear el archivo ".htaccess" con las siguientes líneas, al mismo nivel de "index.php"
~~~
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
~~~
