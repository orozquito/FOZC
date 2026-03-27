<?php
session_start();

// Capturar mensaje de error si viene de procesar_login / guardar_usuarios
$error_msg = "";
if (isset($_GET['login_error']) && isset($_SESSION['error_login'])) {
    $error_msg = $_SESSION['error_login'];
    unset($_SESSION['error_login']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOZC</title>

    <link rel="stylesheet" href="css/styles.css">

    <style>
 
   /* BOTONES HEADER AJUSTADOS */
.auth-buttons {
    display: flex;
    justify-content: center; /* CENTRADO */
    gap: 15px;
    margin-bottom: 8px;
}

.btn-auth {
    background: transparent !important;
    border: 1px solid white !important; 
    color: black !important;
    padding: 8px 20px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 400;
    transition: 0.3s;
}

/* HOVER */
.btn-auth:hover {
    background: black;
    color: black;
    transform: translateY(-2px);
}

    /* MODAL */
    .modal {
        display: none;
        position: fixed;
        z-index: 10;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
    }

    .modal-content {
        background: white;
        width: 320px;
        padding: 25px;
        border-radius: 15px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 0;
        transition: 0.3s;
    }

    .modal.show .modal-content {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }

    .modal-content input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        box-sizing: border-box;
    }

    .modal-content button {
        width: 100%;
        padding: 10px;
        background: transparent;
        border: 2px solid #2b2d31;
        color: #2b2d31;
        border-radius: 25px;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 8px;
    }

    .modal-content button:hover {
        background: #25272b;
        color: black;
    }

    .close {
        float: right;
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
    }

    .switch {
        margin-top: 10px;
        font-size: 14px;
        cursor: pointer;
        color: blue;
        text-align: center;
    }

    .error-msg {
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 10px;
        font-size: 14px;
        text-align: center;
    }

    .user-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .btn-logout {
        background: transparent;
        border: 1px solid white;
        color: black;
        padding: 6px 16px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        transition: 0.3s;
        display: inline-block;
        width: auto;
        margin: 0;
    }

    .btn-logout:hover {
        background: white;
        color: black;
    }
    </style>
</head>

<body>

<header class="header">

<?php if(isset($_SESSION['usuario'])): ?>

    <div class="user-panel">
        <span>👤 <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
        <a href="logout.php" class="btn-logout">CERRAR SESION</a>
    </div>

<?php else: ?>

    <div class="auth-buttons">
        <button class="btn-auth" onclick="abrirModal('login')">INICIAR SESIÓN</button>
        <button class="btn-auth" onclick="abrirModal('registro')">REGISTRARSE</button>
    </div>

<?php endif; ?>

<h1>FOZC</h1>

</header>

<section class="hero">
    <h2>Maquinaria pesada a tu alcance</h2>
    <p>Alquiler, compra y mantenimiento profesional</p>

    <a id="btnContacto" class="btn" 
    href="https://api.whatsapp.com/send?phone=573216185400&text=Hola%20FOZC,%20estoy%20interesado%20en%20sus%20servicios.%20¿Podrían%20brindarme%20más%20información?" 
    target="_blank">
    Contáctanos
    </a>

    <div class="servicios">

    <a href="pages/alquiler.php" class="card-link">
        <div class="card" id="alquiler">
            <h3>ALQUILER</h3>
        </div>
    </a>

    <a href="pages/compra.php" class="card-link">
        <div class="card" id="compra">
            <h3>COMPRA</h3>
        </div>
    </a>
        </div>

        <a href="pages/servicios.php" class="card-link">
            <div class="card" id="servicio">
                <h3>SERVICIO TÉCNICO</h3>
            </div>
        </a>

    </div>
</section>

<footer class="footer">
    <p>&copy; 2026 FOZC</p>
</footer>

<!-- MODAL LOGIN / REGISTRO -->
<div id="modalLogin" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>

        <div id="formLogin">
            <h2>INICIAR SESIÓN</h2>

            <?php if($error_msg): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>

            <form action="procesar_login.php" method="POST">
                <input type="hidden" name="redirect" value="index.php">
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">INGRESAR</button>
            </form>

            <div class="switch" onclick="cambiarFormulario('registro')">
                ¿No tienes cuenta? Regístrate
            </div>
        </div>

        <div id="formRegistro" style="display:none;">
            <h2>REGISTRO</h2>

            <form action="guardar_usuarios.php" method="POST">
                <input type="hidden" name="redirect" value="index.php">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">REGISTRARSE</button>
            </form>

            <div class="switch" onclick="cambiarFormulario('login')">
                ¿Ya tienes cuenta? Inicia sesión
            </div>
        </div>

    </div>
</div>

<script>
// Abrir modal automáticamente si hay error de login
<?php if($error_msg): ?>
window.addEventListener('DOMContentLoaded', function() {
    abrirModal('login');
});
<?php endif; ?>

function abrirModal(tipo) {
    const modal = document.getElementById("modalLogin");
    modal.style.display = "block";
    cambiarFormulario(tipo);
    setTimeout(() => { modal.classList.add("show"); }, 10);
}

function cambiarFormulario(tipo) {
    document.getElementById("formLogin").style.display    = (tipo === "login")    ? "block" : "none";
    document.getElementById("formRegistro").style.display = (tipo === "registro") ? "block" : "none";
}

function cerrarModal() {
    const modal = document.getElementById("modalLogin");
    modal.classList.remove("show");
    setTimeout(() => { modal.style.display = "none"; }, 300);
}

window.onclick = function(e) {
    let modal = document.getElementById("modalLogin");
    if (e.target == modal) cerrarModal();
}
</script>

</body>
</html>
