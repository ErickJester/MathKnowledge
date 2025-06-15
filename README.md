# Maths Knowledge

Este proyecto es una **aplicación web educativa** desarrollada con PHP y MySQL, pensada para subir y consultar archivos PDF (recursos académicos). A continuación encontrarás un manual de instalación y solución de problemas para garantizar que la funcionalidad de subida de PDFs funcione correctamente al clonar este repositorio.

---

## Índice

1. [Requisitos](#requisitos)
2. [Instalación](#instalación)

   1. [Clonar el repositorio](#clonar-el-repositorio)
   2. [Configurar el servidor web](#configurar-el-servidor-web)
   3. [Configurar la base de datos](#configurar-la-base-de-datos)
   4. [Configurar las credenciales de conexión](#configurar-las-credenciales-de-conexión)
   5. [Crear y dar permisos a la carpeta `uploads/`](#crear-y-dar-permisos-a-la-carpeta-uploads)
   6. [Inicializar el directorio de uploads en tiempo de ejecución](#inicializar-el-directorio-de-uploads-en-tiempo-de-ejecución)
3. [Prueba de subida de PDF](#prueba-de-subida-de-pdf)
4. [Solución de problemas comunes](#solución-de-problemas-comunes)
5. [.gitignore recomendado](#gitignore-recomendado)

---

## Requisitos

* **PHP** 7.4 o superior
* **MySQL** 5.7 o superior
* **Servidor web** (Apache, Nginx) o paquete tipo **XAMPP**
* Extensión **mysqli** habilitada

---

## Instalación

### Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/MathsKnowledge.git
cd MathsKnowledge
```

### Configurar el servidor web

* Si usas XAMPP, asegúrate de que la carpeta del proyecto esté bajo `htdocs/`:

  ```bash
  /Applications/XAMPP/xamppfiles/htdocs/ProyectoFEPI
  ```
* Verifica que el **DocumentRoot** de Apache apunte a la carpeta del proyecto.

### Configurar la base de datos

1. Crea la base de datos y tablas ejecutando los scripts SQL incluidos en:

   * `base_biblioteca.sql`
   * `base_biblioteca2.sql`
2. Agrega la columna para almacenar la ruta del PDF en la tabla `Digital`:

   ```sql
   ALTER TABLE Digital
   ADD COLUMN archivo_pdf VARCHAR(255) NOT NULL AFTER id_digital;
   ```

### Configurar las credenciales de conexión

Edite el archivo `includes/conexion.php` con sus datos de acceso MySQL:

```php
<?php
$host     = 'localhost';
$user     = 'tu_usuario';
$password = 'tu_contraseña';
dbname     = 'nombre_base_datos';
$conn = new mysqli($host, $user, $password, $dbname);
if (\$conn->connect_error) {
    die('Error de conexión: ' . \$conn->connect_error);
}
?>
```

### Crear y dar permisos a la carpeta `uploads/`

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/ProyectoFEPI
mkdir uploads
sudo chown -R daemon:daemon uploads   # o www-data:www-data en Linux
chmod 775 uploads
```

> **Nota:** el usuario que ejecute PHP (`daemon` en macOS XAMPP, `www-data` en Linux) debe tener permisos de escritura.

### Inicializar el directorio de uploads en tiempo de ejecución

Para evitar fallos si la carpeta `uploads/` no existe, se recomienda agregar al inicio de `procesar_digital.php`:

```php
$uploadsDir = __DIR__ . '/../../uploads/';
if (!is_dir(\$uploadsDir)) {
    mkdir(\$uploadsDir, 0755, true);
}
```

---

## Prueba de subida de PDF

1. Accede al formulario de registro de medio digital (ej. `subir-pdf.html`).
2. Completa los campos y selecciona un archivo `.pdf`.
3. Envía el formulario.
4. Verifica que el PDF aparezca en la carpeta `uploads/` y que el mensaje confirme su guardado.

---

## Solución de problemas comunes

1. **`is_writable => false`**

   * Revisa la propiedad y permisos de `uploads/` con:

     ```bash
     ls -ld uploads
     ```
   * Ajusta propietario y permisos (ve sección anterior).

2. **`Unknown column 'archivo_pdf'`**

   * Ejecuta el `ALTER TABLE` para agregar la columna.

3. **Ruta incorrecta en `move_uploaded_file`**

   * Confirma que `__DIR__ . '/../../uploads/'` resuelva correctamente (usar `realpath()` para depurar).

4. **Credenciales de base de datos**

   * Asegúrate de que `includes/conexion.php` apunte a la BD correcta y sin errores.

---

## .gitignore recomendado

```gitignore
/uploads/
/includes/conexion.php
.DS_Store
```

> **No** incluyas carpetas de subida ni credenciales en el repositorio para mantener la seguridad y evitar conflictos de permisos.

---

**¡Listo!** Sigue estos pasos y cualquier persona podrá clonar tu proyecto desde GitHub y tener la funcionalidad de subida de PDFs operativa.
