<div>
    <a href="https://barrilete.com.ar/"><img alt="Barrilete" src="https://barrilete.com.ar/svg/logo_barrilete.svg"></a>
    <h2>v2.6</h2>
    <h4>Notas de la versión</h4>
    <ul>
        <li>Se integra Docker al proyecto</li>
        <li>Se integra Laravel websockets (en fase de pruebas aún)</li>
        <li>Upgrade de Laravel a la versión 6.x</li>
        <li>Instalación de la extensión Xdebug para facilitar el debug de la aplicación</li>
    </ul>
    <h3>Pre-requisitos para la instalación del ambiente</h3>
    <p>- Las siguientes instrucciones están hechas solo para entornos de desarrollo basados en GNU/Linux.</p>
    <p>- Si ya tienes instalado en tu sistema <b>Docker</b> y <b>Docker-Compose</b>, mira directamente <a href="#ambiente">Instalación del ambiente en nuestra maquina local</a></p>
    <hr>
    <h3>Instalación de Docker y Docker Compose</h3>
    <p>1. Actualizamos los paquetes del sistema:</p>
    <p><code>$ sudo apt-get update</code></p>
    <p>2. Instale paquetes para permitir que apt use un repositorio sobre HTTPS:</p>
    <p><code>$ sudo apt-get install \
              apt-transport-https \
              ca-certificates \
              curl \
              gnupg-agent \
              software-properties-common
    </code></p>
    <p>3. Agregue la clave GPG oficial de Docker:</p>
    <p><code>$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -</code></p>
    <p>4. Use el siguiente comando para configurar el repositorio estable:</p>
    <p><code>$ sudo add-apt-repository \
             "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
             $(lsb_release -cs) \
             stable"
    </code></p>
    <p>5. Actualizamos los paquetes de nuestro sistema:</p>
    <p><code>$ sudo apt-get update</code></p>
    <p>6. Instale la última versión de Docker Engine</p>
    <p><code>$ sudo apt-get install docker-ce docker-ce-cli containerd.io</code></p>
    <p>7. Verifique que Docker Engine - Community esté instalado correctamente ejecutando la imagen hello-world.</p>
    <p><code>$ sudo docker run hello-world</code></p>
    <p>8. Ejecute este comando para descargar la versión estable actual de Docker Compose:</p>
    <p><code>$ sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose</code></p>
    <p>9. Aplique permisos ejecutables al binario:</p>
    <p><code>$ sudo chmod +x /usr/local/bin/docker-compose</code></p>
    <p>10. Si el comando <b>docker-compose</b> falla después de la instalación, verifique su ruta. También puede crear un enlace simbólico a <b>/ usr / bin</b> o cualquier otro directorio en su ruta.</p>
    <p><code>$ sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose</code></p>
    <p>11. Probamos la instalación:</p>
    <p><code>$ docker-compose --version</code></p>
    <hr>
    <h3 id="ambiente">Instalación del ambiente en nuestra maquina local</h3>
    <p>1. Creamos un <b>Fork</b> en GitHub del proyecto</p>
    <p>2. Clonamos el repositorio de GitHub en nuestra computadora:</p>
    <p><code>$ git clone git@github.com:<b>'nuestro_usuario'</b>/Barrilete.git</code></p>
    <p>3. Configuramos el branch remoto</p>
    <p><code>git remote add barrilete git@github.com:ConradoSM/Barrilete.git</code></p>
    <p>4. Nos traemos todos los branches</p>
    <p><code>git fetch --all</code></p>
    <p>5. Para trabajar siempre partiremos del branch develop</p>
    <p><code>git checkout develop</code> y luego <code>git pull --rebase barrilete develop</code></p>
    <p>6. Corremos el contenedor de docker:</p>
    <p><code>$	docker-compose up -d</code></p>
    <p>7. Instalamos las dependencias de Laravel:</p>
    <p><code>$ docker-compose exec app composer install</code></p>
    <p>8. Generamos el archivo .env</p>
    <p><code>$ cp .env.example .env</code></p>
    <p>9. Si esto es correcto, podemos correr dos comandos mas para adicionar seguridad a nuestra aplicación:</p>
    <p><code>$ docker-compose exec app php artisan key:generate</code></p>
    <p><code>$ docker-compose exec app php artisan config:cache</code></p>
    <p><code>$ docker-compose exec app php artisan config:clear</code></p>
    <p>10. Corremos las migraciones y seeders:</p>
    <p><code>$ docker-compose exec app php artisan migrate</code></p>
    <p><code>$ docker-compose exec app php artisan db:seed</code></p>
    <p>11. Instalamos paquetes de npm</p>
    <p><code>docker-compose exec -u root app npm install</code></p>
    <p>12. Compilación automática de archivos javascript y css durante el desarrollo</p>
    <p><code>docker-compose exec app npm run watch</code></p>    
    <p>13. Editamos el archivo <b>etc/hosts</b> agregando la siguiente línea:</p>
    <p><code>127.0.0.1 local.barrilete.com.ar</code></p>
    <p>14. Probamos el sitio:</p>
    <p><code>http://local.barrilete.com.ar</code></p>
</div>
