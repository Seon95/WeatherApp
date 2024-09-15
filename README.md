# Configuración del Proyecto Laravel con Docker

Este documento describe los pasos necesarios para configurar y ejecutar una aplicación meteorológica sencilla desarrollada con Laravel, Vue.js, y Tailwind CSS, todo dentro de un entorno Docker. La aplicación permite obtener el pronóstico del tiempo para distintos municipios de España, los cuales se almacenan en una base de datos local utilizando un comando de Artisan.

Este enfoque modular y escalable permite una fácil integración con APIs de datos meteorológicos, y gracias a su arquitectura responsive, la aplicación se adapta perfectamente a diferentes dispositivos, garantizando una experiencia fluida tanto en móviles como en escritorio.

## Clonar el Repositorio

Para comenzar, clona el repositorio desde GitHub:

```bash
git clone https://github.com/Seon95/WeatherApp
cd WeatherApp
```

## Pasos de Configuración

### 1. Instalar Dependencias de Composer

```bash
docker run --rm -v $(pwd):/app -w /app composer install
```

Este comando:

-   Ejecuta un contenedor Docker con Composer
-   Monta el directorio actual (`$(pwd)`) en `/app` dentro del contenedor
-   Establece el directorio de trabajo en `/app`
-   Ejecuta `composer install` para instalar las dependencias de PHP
-   Elimina el contenedor después de la ejecución (`--rm`)

### 2. Instalar Dependencias de Node.js

```bash
docker run --rm -v $(pwd):/app -w /app node:alpine npm install
```

Este comando:

-   Ejecuta un contenedor Docker con Node.js (versión Alpine para un tamaño reducido)
-   Monta el directorio actual en `/app` dentro del contenedor
-   Establece el directorio de trabajo en `/app`
-   Ejecuta `npm install` para instalar las dependencias de JavaScript
-   Elimina el contenedor después de la ejecución

### 3. Configurar el Archivo de Entorno

Cambia el archivo `.env.example` a `.env` ,he dejado mi API_KEY ya que caduca en nada (obtén una nueva key en https://opendata.aemet.es/centrodedescargas/altaUsuario)

### 4. Iniciar los Servicios con Docker Compose

```bash
docker-compose up -d
```

Este comando:

-   Inicia todos los servicios definidos en tu archivo `docker-compose.yml`
-   La opción `-d` ejecuta los contenedores en segundo plano

### 5. Ejecutar Migraciones de la Base de Datos

```bash
docker-compose exec app php artisan migrate
```

Este comando:

-   Ejecuta `php artisan migrate` dentro del contenedor de la aplicación
-   Crea o actualiza las tablas de la base de datos según las migraciones definidas

### 6. Obtener Datos de Municipios

```bash
docker-compose exec app php artisan fetch:municipios
```

Este comando:

-   Ejecuta un comando personalizado de Artisan (`fetch:municipios`) dentro del contenedor de la aplicación
-   Presumiblemente, este comando obtiene y almacena datos de municipios en la base de datos

### 7. Resultado

Para ver el resultado, abre tu navegador y accede a http://localhost:8000.
