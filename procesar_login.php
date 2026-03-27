<?php
session_start();

require_once __DIR__ . "/config/db.php";

if (!isset($_POST['correo']) || !isset($_POST['password'])) {
    header("Location: index.php");
    exit();
}

$correo   = trim($_POST['correo']);
$password = $_POST['password'];

// Destino de redirección: primero POST, luego SESSION, luego index
$redirect = "index.php";
if (!empty($_POST['redirect'])) {
    $redirect = $_POST['redirect'];
} elseif (isset($_SESSION['redirect'])) {
    $redirect = $_SESSION['redirect'];
    unset($_SESSION['redirect']);
}

if (!$conn) {
    die("Error: conexión a base de datos no establecida. Ejecuta <a href='setup_db.php'>setup_db.php</a> primero.");
}

$sql  = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en prepare(): " . $conn->error);
}

$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['usuario'] = $user['nombre'];
        header("Location: " . $redirect);
        exit();
    } else {
        // Contraseña incorrecta → volver con error
        $_SESSION['error_login'] = "Contraseña incorrecta. Inténtalo de nuevo.";
        header("Location: " . (strpos($redirect, 'pages/') !== false ? $redirect : "index.php") . "?login_error=1");
        exit();
    }
} else {
    $_SESSION['error_login'] = "No existe una cuenta con ese correo.";
    header("Location: " . (strpos($redirect, 'pages/') !== false ? $redirect : "index.php") . "?login_error=1");
    exit();
}

$stmt->close();
$conn->close();
?>
