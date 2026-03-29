<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
body {
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #0f172a;
    font-family: Arial;
}

.login-box {
    background: black;
    padding: 30px;
    border-radius: 15px;
    width: 300px;
    text-align: center;
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
}

button {
    width: 100%;
    padding: 10px;
    background: #000000;
    color: black;
    border: none;
    cursor: pointer;

    
}
</style>
</head>

<body>

<div class="login-box">
    <h2>IINICIAR SESIÓN</h2>

    <form action="procesar_login.php" method="POST">
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">INGRESAR</button>
    </form>
</div>

</body>
</html>