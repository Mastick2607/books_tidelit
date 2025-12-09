# Prueba Técnica - Gestión de Libros y Reseñas


##  Descripción

Este proyecto es la solución técnica para el desafío de desarrollo Full-Stack, demostrando la integración de un Backend monolítico con API REST que alimenta dos frontends distintos: una Aplicación Web y una Aplicación Móvil nativa.

##  Requisitos Técnicos:
-  XAMPP o entorno de desarrollo web local como WAMP, PHP: Versión 8.1 o superior, Composer (en mi caso version 2.8.4 ), Node.js & npm: Versión 18.x o superior, Base de Datos: MySQL, Node.js & npm/npx: Necesario para ejecutar la CLI de Expo, Expo Go App, vite


##  Arquitectura Utilizada:

-  Backend (API): Symfony 6.4

- Frontend Web: Vue 3 (Composition API) + Vite 

- Frontend Móvil: React Native (Expo Go)

- Base de Datos: MYSQL


##  Instrucciones de instalación (backend):

1. Clonar el repositorio

```bash
  git clone https://github.com/yyyyyyyyy/xxxxxxx.git
  cd xxxxxxxxxxx
  code . //para abrir el proyecto y luego pararse en la rama main, luego parate dentro de la carpeta backend: ../backend
```

2. Instalar composer sino se tiene

- Visita https://getcomposer.org/download/
- Descarga el archivo 'Composer-Setup.exe'
- Sigue las instrucciones del asistente de instalación
- Asegúrate de que la opción "Add to PATH" esté marcada para poder usar composer desde cualquier directorio
- Abre una nueva ventana de CMD o PowerShell y ejecuta:
  
```bash
 composer --version
```

3. Sí ya tienes composer ejecuta esto:
```bash
composer install
```
   
4. Copia el archivo de configuración y configura las variables de entorno:

- Abre el explorador de archivos en la raíz del proyecto.
- Busca el archivo .env.example.
- Cópialo (Ctrl + C) y pégalo (Ctrl + V).
- Renómbralo a .env y configura los datos de la BD.

 DATABASE_URL="mysql://root:PASS_DE_MI_MAQUINA@127.0.0.1:3306/nombre_db"
 APP_SECRET='una-clave-secreta-nueva-y-unica'

5.descomentar esta linea de codigo en la ruta: config/packages/fos_rest.yaml:


 ```bash
   format_listener:
       rules:
           - { path: ^/api, prefer_extension: true, fallback_format: json, priorities: [ json, html ] }

```


6. Agregar esta linea en la ruta : config/packages/framework.yaml


```bash
   #esi: true
    #fragments: true
    php_errors:
        log: true
    
    // esta  3 lineas de abajo
    serializer:
        enabled: true
        enable_annotations: true
```
7. crea la base de datos
(antes de colocar este comando debes de arrancar al servidor local):

```bash
 php bin/console doctrine:database:create
```

8. crea las migraciones y ejecutarlas:

```bash
 php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

9. Crear un BookFixtures php con los 3 libros y 6 reseñas solicitados:

```bash
php bin/console doctrine:fixtures:load
```

7. Arrancar el servidor local:

```bash
(en este caso no utilices este comando : php -S localhost:8000 )
php -S 0.0.0.0:8000 -t public  
```

## Correr el frontend mobile (React Native / Expo):

1. Entrar a la carpeta mobile/BookApp : cd .mobile/BookApp.

2. instalar carpeta node_modules

```bash
  npm install
```

4. Copia el archivo de configuración y configura las variables de entorno:

-  en la raíz del proyecto : mobile/BookApp .
- Busca el archivo .env.example.
- Cópialo (Ctrl + C) y pégalo (Ctrl + V).
- Renómbralo a .env y configura los datos de la BD.
- cambia la variable de entorno "IP" Por tu IP local

5. si no sabes cual es tu ip sigue estos pasos:

- abre tu terminal  y pararte en la carpeta y ejecuta: 

```bash
  ipconfig
```

- Busca la sección que dice "Adaptador de Ethernet" o "Adaptador de LAN inalámbrica Wi-Fi"
Tu dirección es la que pone Dirección IPv4 esa va en la varia de entorno "IP".

- Luego ejecuta npm install expo (ejecutalo dentro de la carpeta bookapp)

- Luego instala (ejecutalo dentro de la carpeta bookapp) 

```bash
 npm install expo 
```

- Luego instala

```bash
npx expo start -c 
```
- Una vez tengas esto  debes instalar expo go y con la opcion de escanear, escaneas el codigo qr que me devuelve el comando
npx expo start -c te proporciona, asi podras ver en tiempo real la app movil en tu celular


## Correr el frontend  frontend web (Vue):

1. Entrar a la carpeta cd vue-books-frontend : cd .frontend/vue-books-frontend.

2. instalar carpeta node_modules

```bash
  npm install
```
3. ejecuta:
```bash
  npm run dev
```


#  Endpoints Principales

## Productos

*GET*  http://localhost:8000/api/books 

*POST*  http://localhost:8000/api/reviews

```bash
  {
  "book_id": 1,
  "rating": 2,
  "comment": "en mi opinion es excelente libro"
}

```

#  Captura o curl que muestre el endpoint funcionando



# Respuestas esperadas ante errores de validación.


## ¿Qué cambiarías para escalar esta app a cientos de miles de libros y usuarios?

- El cambio más crítico es asegurar que la base de datos pueda manejar el volumen de datos y tráfico.

Implementar Replicación: Configurar réplicas  de lectura de la base de datos principal. Esto permite que el 90% del tráfico (lecturas de listas de libros) vaya a los esclavos, liberando al maestro para las escrituras (creación de reseñas).

Optimización de Consultas: Revisar y optimizar los queries lentos, especialmente aquellos que calculan el rating promedio, asegurando que existan índices adecuados en campos como book_id y published_year.

El caching es el escudo que protege la base de datos del alto tráfico.

Caching de Capa de Aplicación: Integrar un sistema de caché rápido como Redis o Memcached en Symfony.

Caché de Listas: Almacenar la lista completa de libros (/api/books) y sus ratings en la caché por un tiempo corto (ej., 5 minutos). Esto evita que miles de peticiones golpeen la base de datos innecesariamente.

## Decisiones técnicas:
- Capa de Servicios: Toda la lógica de negocio, como la búsqueda de entidades, la persistencia en base de datos, y el manejo de excepciones de negocio , fue trasladada a las clases BookService y ReviewService.

- DTOS: Se creó el  CreateReviewRequest para manejar la entrada de datos del endpoint POST /api/reviews. Las validaciones de Symfony (Assert) se colocaron en el DTO, no en la entidad.

- Cálculo de Rating Eficiente: El promedio de calificación (average_rating) se calcula en el BookRepository con la función de agregación AVG(). En el BookService, se utiliza un operador ternario para convertir los valores NULL (devueltos por AVG() cuando un libro no tiene reseñas) a un entero 0.

# Video evidencia:
## branch evaluado y commit final:
- main

## commit final:
- 