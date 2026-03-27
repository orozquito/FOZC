<?php
session_start();

$error_msg = "";
if (isset($_GET['login_error']) && isset($_SESSION['error_login'])) {
    $error_msg = $_SESSION['error_login'];
    unset($_SESSION['error_login']);
}

function generarWhatsApp($producto, $precio) {
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "cliente";
    $mensaje = "Hola, soy $usuario y estoy interesado en comprar $producto con un valor de $precio.";
    return "https://api.whatsapp.com/send?phone=573216185400&text=" . urlencode($mensaje);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra - FOZC</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
    .modal { 
        display:none; 
        position:fixed; 
        z-index:100; 
        left:0; 
        top:0;
        width:100%;
        height:100%; 
        background:rgba(0,0,0,0.7); 
        }
    .modal-content { 
        background:white; 
        width:320px; 
        padding:25px;
        border-radius:15px;
        position:absolute; 
        top:50%; left:50%; 
        transform:translate(-50%,-50%) scale(0.8); 
        opacity:0; 
        transition:0.3s; 
        }
    .modal.show .modal-content { 
        transform:translate(-50%,-50%) scale(1); 
        opacity:1; 
    }
    .modal-content input { 
        width:100%; 
        padding:10px; 
        margin:10px 0; 
        box-sizing:border-box; 
    }
    .modal-content button { 
        width:100%; 
        padding:10px; 
        background:transparent;
        border:2px solid #000000; 
        color:#000000; 
        border-radius:25px; 
        cursor:pointer; 
        transition:0.3s; 
        margin-top:8px; 
    }
    .modal-content button:hover { 
        background:#000000; 
        color:white; 
    }
    .close { 
        float:right; 
        cursor:pointer; 
        font-size:20px; 
        line-height:1; 
    }
    .switch { 
        margin-top:10px; 
        font-size:14px; 
        cursor:pointer; 
        color:black; 
        text-align:center; 
    }
    .error-msg { 
        background:#fee2e2; 
        color:#b91c1c; 
        border-radius:8px; 
        padding:8px 12px; 
        margin-bottom:10px; 
        font-size:14px; 
        text-align:center; 
    }
    .user-panel { 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        gap:12px; 
        margin-bottom:8px; 
    }
    .btn-logout { 
        background:transparent; 
        border:1px solid black; 
        color:black; 
        padding:6px 16px; 
        border-radius:20px; 
        cursor:pointer; 
        font-size:14px; 
        text-decoration:none; 
        transition:0.3s; 
        display:inline-block; 
        width:auto; 
        margin:0; 
    }
    .btn-logout:hover { 
        background:black; 
        color:white; 
    }
    </style>
</head>
<body>

<header class="header">
<?php if(isset($_SESSION['usuario'])): ?>
    <div class="user-panel">
        <span>👤 <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
        <a href="../logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
<?php else: ?>
    <div style="display:flex;justify-content:center;gap:15px;margin-bottom:8px;">
        <button style="background:transparent;border:1px solid white;color:black;padding:8px 20px;border-radius:20px;cursor:pointer;font-size:15px;font-weight:400;transition:0.3s;" onclick="abrirModal('login')">INICIAR SESIÓN</button>
        <button style="background:transparent;border:1px solid white;color:black;padding:8px 20px;border-radius:20px;cursor:pointer;font-size:15px;font-weight:400;transition:0.3s;" onclick="abrirModal('registro')">REGISTRARSE</button>
    </div>
<?php endif; ?>
    <h1>FOZC</h1>
</header>

<a href="../index.php" class="back-button">←</a>

<section class="alquiler-section compra-bg">
    <h2>Maquinaria en Venta</h2>
    <p>Adquiere maquinaria de alta calidad para tus proyectos.</p>

    <div class="maquinaria-grid">

        <div class="card">
            <h3>Excavadora</h3>
            <p><strong>Precio:</strong> $450,000,000</p>
            <?php if(isset($_SESSION['usuario'])): ?>
                <a href="<?php echo generarWhatsApp('Excavadora', '$450,000,000'); ?>" target="_blank"><button>Comprar</button></a>
            <?php else: ?>
                <button onclick="abrirModal('login')">Comprar</button>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>Retroexcavadora</h3>
            <p><strong>Precio:</strong> $320,000,000</p>
            <?php if(isset($_SESSION['usuario'])): ?>
                <a href="<?php echo generarWhatsApp('Retroexcavadora', '$320,000,000'); ?>" target="_blank"><button>Comprar</button></a>
            <?php else: ?>
                <button onclick="abrirModal('login')">Comprar</button>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>Cargador Frontal</h3>
            <p><strong>Precio:</strong> $280,000,000</p>
            <?php if(isset($_SESSION['usuario'])): ?>
                <a href="<?php echo generarWhatsApp('Cargador Frontal', '$280,000,000'); ?>" target="_blank"><button>Comprar</button></a>
            <?php else: ?>
                <button onclick="abrirModal('login')">Comprar</button>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>Grúa</h3>
            <p><strong>Precio:</strong> $600,000,000</p>
            <?php if(isset($_SESSION['usuario'])): ?>
                <a href="<?php echo generarWhatsApp('Grúa', '$600,000,000'); ?>" target="_blank"><button>Comprar</button></a>
            <?php else: ?>
                <button onclick="abrirModal('login')">Comprar</button>
            <?php endif; ?>
        </div>

    </div>
</section>

<footer><p>© 2026 FOZC</p></footer>

<!-- MODAL LOGIN / REGISTRO -->
<div id="modalLogin" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>

        <div id="formLogin">
            <h2>Iniciar Sesión</h2>
            <?php if($error_msg): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>
            <form action="../procesar_login.php" method="POST">
                <input type="hidden" name="redirect" value="pages/compra.php">
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
            </form>
            <div class="switch" onclick="cambiarFormulario('registro')">¿No tienes cuenta? Regístrate</div>
        </div>

        <div id="formRegistro" style="display:none;">
            <h2>Registro</h2>
            <form action="../guardar_usuarios.php" method="POST">
                <input type="hidden" name="redirect" value="pages/compra.php">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Registrarse</button>
            </form>
            <div class="switch" onclick="cambiarFormulario('login')">¿Ya tienes cuenta? Inicia sesión</div>
        </div>
    </div>
</div>

<script>
<?php if($error_msg): ?>
window.addEventListener('DOMContentLoaded', function() { abrirModal('login'); });
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
window.onclick = function(e) { if (e.target == document.getElementById("modalLogin")) cerrarModal(); }
</script>

</body>
</html>
