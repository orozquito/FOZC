<?php
session_start();
include("config/db.php");

if (!isset($_POST['nombre']) || !isset($_POST['correo']) || !isset($_POST['password'])) {
    header("Location: index.php");
    exit();
}

$nombre   = trim($_POST['nombre']);
$correo   = trim($_POST['correo']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Destino de redirección: primero POST, luego SESSION, luego index
$redirect = "index.php";
if (!empty($_POST['redirect'])) {
    $redirect = $_POST['redirect'];
} elseif (isset($_SESSION['redirect'])) {
    $redirect = $_SESSION['redirect'];
    unset($_SESSION['redirect']);
}

// Verificar si el correo ya existe
$check = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
$check->bind_param("s", $correo);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $_SESSION['error_login'] = "Ya existe una cuenta con ese correo. Inicia sesión.";
    header("Location: " . (strpos($redirect, 'pages/') !== false ? $redirect : "index.php") . "?login_error=1");
    exit();
}
$check->close();

$sql  = "INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nombre, $correo, $password);

if ($stmt->execute()) {
    $_SESSION['usuario'] = $nombre;
    header("Location: " . $redirect);
    exit();
} else {
    $_SESSION['error_login'] = "Error al registrar. Inténtalo de nuevo.";
    header("Location: " . (strpos($redirect, 'pages/') !== false ? $redirect : "index.php") . "?login_error=1");
    exit();
}
?>
