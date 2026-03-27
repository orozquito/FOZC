<?php
session_start();
include("config/db.php");

$correo = $_POST['correo'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {

    if (password_verify($password, $user['password'])) {

        $_SESSION['usuario'] = $user['nombre'];

        if (isset($_SESSION['redirect'])) {
            $destino = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
            header("Location: " . $destino);
        } else {
            header("Location: index.php");
        }

    } else {
        echo "Contraseña incorrecta";
    }

} else {
    echo "Usuario no encontrado";
}
?>