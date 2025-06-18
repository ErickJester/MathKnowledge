<?php
// Pages/logout.php
session_start();

// Vacía todas las variables de sesión
$_SESSION = [];

// Elimina la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruye la sesión
session_destroy();

// Redirige al inicio público
header('Location: ../indexMath.php');
exit;
