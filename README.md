# Maths Knowledge

> 🚧 **Estado:** Este proyecto está en construcción y puede estar incompleto. Algunas funcionalidades están en desarrollo y el contenido está sujeto a cambios.

Este proyecto es una **aplicación web educativa** desarrollada con PHP y MySQL para subir y consultar recursos académicos (PDFs). Este documento describe desde cero el proceso de instalación, configuración y solución de problemas al clonar el repositorio.

---

## Índice

1. [Requisitos](#requisitos)
2. [Instalación](#instalación)

   1. [Clonar el repositorio](#clonar-el-repositorio)
   2. [Configurar el servidor web](#configurar-el-servidor-web)
   3. [Crear la base de datos y tablas](#crear-la-base-de-datos-y-tablas)
   4. [Configurar las credenciales de conexión](#configurar-las-credenciales-de-conexi%C3%B3n)
   5. [Crear y dar permisos a la carpeta `uploads/`](#crear-y-dar-permisos-a-la-carpeta-uploads)
   6. [Inicializar el directorio de uploads en tiempo de ejecución](#inicializar-el-directorio-de-uploads-en-tiempo-de-ejecuci%C3%B3n)
3. [Prueba de subida de PDF](#prueba-de-subida-de-pdf)
4. [Solución de problemas comunes](#soluci%C3%B3n-de-problemas-comunes)
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

* Si usas XAMPP en macOS, coloca el proyecto dentro de `htdocs/`, por ejemplo:

  ```bash
  /Applications/XAMPP/xamppfiles/htdocs/MathsKnowledge
  ```
* En Linux (Apache), podría ser `/var/www/html/MathsKnowledge`.
* Asegúrate de que el **DocumentRoot** de tu virtual host apunte a la carpeta del proyecto.

### Crear la base de datos y tablas

1. Accede a tu consola MySQL o phpMyAdmin.
2. Crea la base de datos **baseMath**:

   ```sql
   CREATE DATABASE baseMath CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Selecciona la base de datos recién creada:

   ```sql
   USE baseMath;
   ```
4. Ejecuta el script SQL incluido (`baseMath.sql`):

   ```sql
   SOURCE baseMath.sql;
   ```
5. Agrega la columna para almacenar la ruta del PDF en la tabla `Digital`:

   ```sql
   ALTER TABLE Digital
     ADD COLUMN archivo_pdf VARCHAR(255) NOT NULL AFTER id_digital;
   ```
6. Inserta los tipos de usuario predeterminados en `Tipo_usuario`:

   ```sql
   INSERT INTO Tipo_usuario (tipo_usuario) VALUES (1),(2),(3);
   ```

### Configurar las credenciales de conexión

Edita el archivo `includes/conexion.php` con tus datos de acceso MySQL:

```php
<?php
$host     = 'localhost';
$user     = 'TU_USUARIO';
$password = 'TU_CONTRASEÑA';
$dbname   = 'baseMath';
$conn = new mysqli($host, $user, $password, $dbname);
if (\$conn->connect_error) {
    die('Error de conexión: ' . \$conn->connect_error);
}
?>
```

---

### Crear y dar permisos a la carpeta `uploads/`

La carpeta `uploads/` debe existir en la raíz del proyecto y ser escribible por el proceso PHP. Cada entorno usa un usuario distinto para PHP. Para detectarlo temporalmente, añade este bloque al inicio de `procesar_digital.php`:

```php
// Depuración de usuario PHP
echo 'PHP user: ' . trim(shell_exec('whoami'));
exit;
```

Al ejecutar el formulario, verás el nombre del usuario. Usa ese usuario para cambiar propiedad y permisos:

```bash
cd /ruta/a/MathsKnowledge
mkdir -p uploads
sudo chown -R <usuario_php>:<grupo_php> uploads
chmod 775 uploads
```

> **Ejemplo:** si la salida de `whoami` es `daemon`, usarás `daemon:daemon`; si es `www-data`, usarás `www-data:www-data`, etc.

---

### Inicializar el directorio de uploads en tiempo de ejecución

Para garantizar que `uploads/` exista sin intervención manual, agrega al inicio de `procesar_digital.php`:

```php
$uploadsDir = __DIR__ . '/../../uploads/';
if (!is_dir(\$uploadsDir)) {
    mkdir(\$uploadsDir, 0755, true);
}
```

Este fragmento creará la carpeta si no está presente.

---

## Prueba de subida de PDF

1. Abre el formulario de registro de medio digital (p.ej. `subir-pdf.html`).
2. Completa los campos y selecciona un archivo `.pdf`.
3. Envía el formulario.
4. Verifica que el PDF aparezca en `uploads/` y que se muestre un mensaje de éxito.

---

## Solución de problemas comunes

1. **Permisos de `uploads/` (`is_writable => false`)**

   * Verifica permisos y propietario:

     ```bash
     ls -ld uploads
     ```
   * Asegúrate de que el propietario/grupo coincida con el usuario de PHP, usando el paso de detección anterior.

2. **Columna `archivo_pdf` desconocida**

   * Revisa que hayas ejecutado:

     ```sql
     ALTER TABLE Digital
       ADD COLUMN archivo_pdf VARCHAR(255) NOT NULL AFTER id_digital;
     ```

3. **Errores de ruta**

   * Usa `realpath(__DIR__ . '/../../uploads/')` dentro de `procesar_digital.php` para depurar rutas.

4. **Credenciales incorrectas**

   * Verifica el contenido de `includes/conexion.php` y asegúrate de que `$dbname` sea `baseMath`.

---

## .gitignore recomendado

```gitignore
/uploads/
/includes/conexion.php
.DS_Store
```

> **Importante:** No incluyas la carpeta `uploads/` ni tus credenciales en el repositorio para evitar problemas de seguridad y portabilidad.

---

**¡Listo!** Con este README, cualquier desarrollador podrá clonar el proyecto, configurar su entorno y disponer de la funcionalidad de subida de PDFs operativa.
