> # API Contempora
> ## Pasos previos
> ### Instalando las dependencias
> Antes de iniciar, debemos descargar (o actualizar) las dependencias de laravel correspondientes con el siguiente comando

<pre>
    <code>
        composer install
    </code>
</pre>

> + Nota: Puede pedir actualizaciones, por lo cual deberemos utilizar el siguiente comando

<pre>
    <code>
        composer update
    </code>
</pre>

> ### Crear/copiar archivo .env
> Luego, deberemos crear el archivo .env, en este caso podemos copiarlo desde el archivo .env.example hacía la carpeta raíz del proyecto (podemos buscar directamente el archivo .env.example con ctrl+p y el nombre del archivo, para copiar su contenido y luego crear el archivo .env en la raíz).
> 
> Con el archivo .env creado, deberemos generar una clave para el, utilizaremos este comando

<pre>
    <code>
        php artisan key:generate
    </code>
</pre>

> ### Levantando el servidor
> Con todos los pasos previos hechos, primero lanzaremos el comando siguiente para refrescar los datos

<pre>
    <code>
        php artisan optimize 
    </code>
</pre>

> Finalmente, utilizaremos este comando para levantar nuestro servidor

<pre>
    <code>
        php artisan serve
    </code>
</pre>

> + Nota: La url base será la que nos muestre la terminal,
> + Ej => Starting Laravel development server: http://127.0.0.1:8000 (url base)


> # Consultas API
> ## GET /usuarios
> 
> Para obtener el listado de los usuarios, utilizar endpoint 
> 
<pre>
    <code>
        GET /usuarios
    </code>
</pre>

> Ej: url_base/usuarios

> ## GET /usuarios?nombre={nombre}
> Para obtener los usuarios filtrados por nombre, utilizar endpoint

<pre>
    <code>
        GET /usuarios?nombre={nombre}
    </code>
</pre>

> Ej: url_base/usuarios?nombre=Ambar 
> + En caso de ir en blanco, se hará una consulta sin filtro
> + Si no se encuentra el nombre, devolverá un mensaje indicando que no hubo resultados para ese valor

> ## GET usuarios?email={email}
> Para obtener los usuarios filtrados por email, utilizar endpoint

<pre>
    <code>
        GET /usuarios?email={email}
    </code>
</pre>

> Ej: url_base/usuarios?email=devvrat_marar@friesen.biz
> + En caso de ir en blanco, se hará una consulta sin filtro
> + Si no se encuentra el email, devolverá un mensaje indicando que no hubo resultados para ese valor

> ## GET /usuarios?activos={true/false}
> Para obtener los usuarios filtrados por su estado activo, utilizar endpoint
> 
<pre>
    <code>
        GET /usuarios?activos={true/false}
    </code>
</pre>

> Ej: url_base/usuarios?activos=true
> + Solo se aceptan como valores validos true y false (puede ser en mayusculas o minusculas), en caso de enviar un valor distinto a esos dos, se retornará un mensaje de error

> ## POST /usuarios
> Para crear un usuario, se debe enviar un JSON con los datos del usuario a crear al siguiente endpoint
<pre>
    <code>
        POST /usuarios
    </code>
</pre>
> + Si se creó con éxito, recibirá un mensaje con código 201 y el JSON del usuario creado

> Formato JSON

<pre>
    <code>
        {
            "nombre":"nombre",
            "email": "email",
            "genero": "male/female",
            "activo": true/false
        }
    </code>
</pre>

> + nombre: String con el nombre del usuario (No puede ir en blanco, en caso de no cumplir, retornará un mensaje de error)
> + email: String con el email del usuario (No puede ir en blanco y debe ser único, en caso de repetirse, retornará un mensaje de error)
> + genero: String con el genero del usuario (Solo puede ser male/female y no puede ir en blanco, en caso de no cumplir, retornará un mensaje de error)
> + activo: Boolean con el estado del usuario (Solo puede ser true/false, sin comillas, en caso de no cumplir, retornará un mensaje de error)

> ## PUT /usuarios/{id}
> Para actualizar un usuario en base a su ID, se debe enviar un JSON con los datos a actualizar al siguiente endpoint

<pre>
    <code>
        PUT /usuarios/{id}
    </code>
</pre>

> + En caso de que no se encuentre un usuario con ese ID, se retornará un mensaje de error 

> Formato JSON

<pre>
    <code>
        {
            "nombre":"nombre",
            "email": "email",
            "genero": "male/female",
            "activo": true/false
        }
    </code>
</pre>

> + nombre: String con el nombre del usuario (No puede ir en blanco, en caso de no cumplir, retornará un mensaje de error)
> + email: String con el email del usuario (No puede ir en blanco y debe ser único, en caso de repetirse, retornará un mensaje de error)
> + genero: String con el genero del usuario (Solo puede ser male/female y no puede ir en blanco, en caso de no cumplir, retornará un mensaje de error)
> + activo: Boolean con el estado del usuario (Solo puede ser true/false, sin comillas, en caso de no cumplir, retornará un mensaje de error)

> ## PUT /usuarios?email{email}
> Para actualizar un usuario en base a su email, se debe enviar un JSON con los datos a actualizar al siguiente endpoint

<pre>
    <code>
        PUT /usuarios?email{email}
    </code>
</pre>

> + En caso de que no se encuentre un usuario con ese email, se retornará un mensaje de error 

> Formato JSON

<pre>
    <code>
        {
            "nombre":"nombre",
            "email": "email",
            "genero": "male/female",
            "activo": true/false
        }
    </code>
</pre>

> + nombre: String con el nombre del usuario (No puede ir en blanco, en caso de no cumplir, retornará un mensaje de error)
> + email: String con el email del usuario (No puede ir en blanco y debe ser único, en caso de repetirse, retornará un mensaje de error)
> + genero: String con el genero del usuario (Solo puede ser male/female y no puede ir en blanco, en caso de no cumplir, retornará un mensaje de error)
> + activo: Boolean con el estado del usuario (Solo puede ser true/false, sin comillas, en caso de no cumplir, retornará un mensaje de error)



