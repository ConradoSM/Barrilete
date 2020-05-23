<div style="text-align: center;">
    <a href="https://barrilete.com.ar/"><img alt="Barrilete" src="https://barrilete.com.ar/svg/logo_barrilete.svg"></a>
    <h2>v2.5</h2>
        <h4>Notas de la versión</h4>
            <ul>
                <li>Se incorpora Docker y Docker-Compose al proyecto</li>
                <li>Upgrade de laravel a la versión 6.x</li>
                <li>Instalación de la extensión Xdebug</li>
            </ul>
        <h3>Pre-requisitos para la instalación del ambiente</h3>
            <p><b>Nota</b>: Las siguientes instrucciones están hechas solo para entornos de desarrollo basados en GNU/Linux.</p>
            <h4>Instalar docker y docker compose</h4>
            <p><b>Nota</b>: Si ya tienes instalado en tu sistema Docker y Docker-Compose, mira directamente <b>Instalación del ambiente en nuestra maquina local</b></p>
                <hr>
                <p>1. Actualizamos los paquetes del sistema:</p>
                <code>$ sudo apt-get update</code><br>
                <hr>
                <p>2. Instale paquetes para permitir que apt use un repositorio sobre HTTPS:</p>
                <code>$ sudo apt-get install \
                          apt-transport-https \
                          ca-certificates \
                          curl \
                          gnupg-agent \
                          software-properties-common
                </code><br>
                <hr>
                <p>3. Agregue la clave GPG oficial de Docker:</p>
                <code>$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -</code><br>
                <hr>
                <p>4. Use el siguiente comando para configurar el repositorio estable:</p>
                <code>$ sudo add-apt-repository \
                         "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
                         $(lsb_release -cs) \
                         stable"
                </code><br>
                <hr>
                <p>5. Actualizamos los paquetes de nuestro sistema:</p>
                <code>$ sudo apt-get update</code><br>
                <hr>
                <p>6. Instale la última versión de Docker Engine: comunidad y contenedor</p>
                <code>$ sudo apt-get install docker-ce docker-ce-cli containerd.io</code><br>
                <hr>
                <p>7. Verifique que Docker Engine - Community esté instalado correctamente ejecutando la imagen hello-world.</p>
                <code>$ sudo docker run hello-world</code>
                <hr>
            <h4>Instalar Docker Compose</h4>
                <p>1. Ejecute este comando para descargar la versión estable actual de Docker Compose:</p>
                <code>$ sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose</code><br>
                <hr>
                <p>2. Aplique permisos ejecutables al binario:</p>
                <code>$ sudo chmod +x /usr/local/bin/docker-compose</code><br>
                <hr>
                <p>3. Si el comando <b>docker-compose</b> falla después de la instalación, verifique su ruta. También puede crear un enlace simbólico a <b>/ usr / bin</b> o cualquier otro directorio en su ruta.</p>
                <code>$ sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose</code><br>
                <hr>
                <p>4. Probamos la instalación:</p>
                <code>$ docker-compose --version</code>
            <hr>
            <h4>Instalación del ambiente en nuestra maquina local</h4>
                <p>1. Creamos un <b>Fork</b> en GitHub del proyecto</p>
                <hr>
                <p>2. Clonamos el repositorio de GitHub en nuestra computadora:</p>
                <code>$ git clone git@github.com:<b>'nuestro_usuario'</b>/Barrilete.git</code><br>
                <hr>
                <p>3. Corremos el contenedor de docker:</p>
                <code>$	docker-compose up -d</code><br>
                <hr>
                <p>4. Instalamos las dependencias de Laravel:</p>
                <code>$ docker-compose exec app composer install</code>
                <hr>
                <p>5. Generamos el archivo .env</p>
                <code>$ cp .env.example .env</code>
                <hr>
                <p>6. Si esto es correcto, podemos correr dos comandos mas para adicionar seguridad a nuestra aplicación:</p>
                <code>$ docker-compose exec app php artisan key:generate</code><br>
                <code>$ docker-compose exec app php artisan config:cache</code><br>
                <code>$ docker-compose exec app php artisan config:clear</code><br>
                <hr>
                <p>7. Corremos las migraciones y seeders:</p>
                <code>$ docker-compose exec app php artisan migrate</code><br>
                <code>$ docker-compose exec app php artisan db:seed</code>
                <hr>
                <p>8. Editamos el archivo <b>etc/hosts</b> agregando la siguiente línea:</p>
                <code>127.0.0.1     local.barrilete.com.ar</code><br>
                <hr>
                <p>9. Probamos el sitio:</p>
                <code>http://local.barrilete.com.ar</code><br>
</div>
