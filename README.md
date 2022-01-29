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

## ¿Cómo realizar el llamado del API?

GET
Ruta: {RUTA_BASE}/{NOMBRE_CARPETA}/v1/albums?q={ARTISTA}

Ejemplos : 
1. Si la carpeta se llama "apiSpotify"
>http://localhost/apiSpotify/v1/albums?q=Bad%20Bunny

2. Si la carpeta se llama "api"
>http://localhost/api/v1/albums?q=Pedro%20Suarez%20Vertiz
