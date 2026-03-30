<?php
session_start();

$error_msg = "";
if (isset($_GET['login_error']) && isset($_SESSION['error_login'])) {
    $error_msg = $_SESSION['error_login'];
    unset($_SESSION['error_login']);
}

function generarWhatsApp($producto, $precio, $proveedor = "FOZC") {
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "cliente";
    $mensaje = "Hola, soy $usuario y estoy interesado en comprar $producto con un valor de $precio (Proveedor: $proveedor).";
    return "https://api.whatsapp.com/send?phone=573216185400&text=" . urlencode($mensaje);
}

$logueado = isset($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra-FOZC</title>
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
        top:50%; 
        left:50%; 
        transform:translate(-50%,-50%) scale(0.8); 
        opacity:0; 
        transition:0.3s; 
    }

    .modal.show .modal-content { 
        transform:translate(-50%,-50%)scale(1);
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
        color:#000000; border-radius:25px; 
        cursor:pointer; 
        transition:0.3s; 
        margin-top:8px; 
    }

    .modal-content button:hover { 
        background:#000000; 
        color:white; 
    }

    .close { float:right; 
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

    .btn-proveedores { 
        width:100%; 
        padding:7px 10px; 
        margin-top:6px; 
        background:transparent; 
        border:1px solid rgba(0,0,0,0.35); 
        color:#000; 
        border-radius:10px; 
        cursor:pointer; 
        font-size:0.78rem; 
        font-weight:600; 
        letter-spacing:0.03em; 
        transition:0.25s; 
        display:block; 
    }
    
    .btn-proveedores:hover { 
        background:rgba(0,0,0,0.08); 
        border-color:rgba(0,0,0,0.6); 
        transform:translateY(-1px); 
    }

    
    #modalProveedores { 
        display:none; 
        position:fixed; 
        z-index:200; 
        left:0; 
        top:0; 
        width:100%; 
        height:100%; 
        background:rgba(0,0,0,0.75); 
    }
    
    .prov-content { 
        background:white; 
        width:min(460px,92%); 
        padding:28px; 
        border-radius:18px; 
        position:absolute; 
        top:50%; left:50%; 
        transform:translate(-50%,-50%) scale(0.85); 
        opacity:0; 
        transition:0.3s; 
        max-height:85vh; 
        overflow-y:auto; 
    }
    
    #modalProveedores.show .prov-content { 
        transform:translate(-50%,-50%)scale(1); 
        opacity:1; 
    }
    
    .prov-titulo { 
        font-size:1.1rem; 
        font-weight:700; 
        margin-bottom:4px; 
    }
    
    .prov-subtitulo { 
        font-size:0.85rem; 
        color:#555; 
        margin-bottom:18px; 
    }
    
    .prov-lista {
        display:flex; 
        flex-direction:column; 
        gap:12px;
    }

    
    .prov-card { 
        border:1px solid rgba(0,0,0,0.15); 
        border-radius:12px; 
        padding:14px 16px; 
        display:flex; 
        align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; transition:0.2s; }
    
    .prov-card:hover { background:rgba(0,0,0,0.03); border-color:rgba(0,0,0,0.3); }
    
    .prov-info { flex:1; }
    
    .prov-nombre { 
        font-weight:700; 
        font-size:0.95rem; 
    }
    
    .prov-precio { 
        font-size:0.92rem; 
        color:#0d6e3a; 
        font-weight:600; 
        margin-top:3px; }
    
    .prov-badge { 
        font-size:0.7rem; 
        padding:2px 8px;
        border-radius:20px; 
        font-weight:700; 
        white-space:nowrap; 
    }
    
    .badge-caro  { 
        background:#fee2e2; 
        color:#b91c1c; 
    }
    
    .badge-medio { 
        background:#fef3c7; 
        color:#92400e; }
    
    .badge-eco   { 
        background:#dcfce7; 
        color:#15803d; 
    }
    
    .prov-btn { 
        padding:7px 16px; 
        border-radius:20px; 
        border:none; 
        background:#0d3e26; 
        color:white; 
        cursor:pointer; 
        font-size:0.82rem; 
        font-weight:600; 
        transition:0.25s; 
        width:auto; 
        margin:0; 
        display:inline-block; 
        white-space:nowrap; 
    }
    
    .prov-btn:hover { 
        background:#145c38; 
        transform:translateY(-1px); 
    }
    
    .prov-close { 
        float:right; 
        cursor:pointer; 
        font-size:20px; 
        line-height:1; 
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
    <div 
    style=
    "display:flex;
    justify-content:center;
    gap:15px;
    margin-bottom:8px;">

        <button style=
        "background:transparent;
        border:1px solid white;
        color:black;padding:8px 20px;
        border-radius:20px;
        cursor:pointer;
        font-size:15px;
        font-weight:400;
        transition:0.3s;" 

        onclick="abrirModal('login')">INICIAR SESIÓN</button>

        <button 
        style=
        "background:transparent;
        border:1px solid white;
        color:black;
        padding:8px 20px;
        border-radius:20px;
        cursor:pointer;
        font-size:15px;
        font-weight:400;
        transition:0.3s;"

         onclick="abrirModal('registro')">REGISTRARSE</button>
         
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
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Excavadora','$450,000,000'); ?>" target="_blank"><button>Comprar</button></a><?php else: ?><button onclick="abrirModal('login')">Comprar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Excavadora')">🏢 Más proveedores</button>
        </div>

        <div class="card">
            <h3>Retroexcavadora</h3>
            <p><strong>Precio:</strong> $320,000,000</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Retroexcavadora','$320,000,000'); ?>" target="_blank"><button>Comprar</button></a><?php else: ?><button onclick="abrirModal('login')">Comprar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Retroexcavadora')">🏢 Más proveedores</button>
        </div>

        <div class="card">
            <h3>Cargador Frontal</h3>
            <p><strong>Precio:</strong> $280,000,000</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Cargador Frontal','$280,000,000'); ?>" target="_blank"><button>Comprar</button></a><?php else: ?><button onclick="abrirModal('login')">Comprar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Cargador Frontal')">🏢 Más proveedores</button>
        </div>

        <div class="card">
            <h3>Grúa</h3>
            <p><strong>Precio:</strong> $600,000,000</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Grúa','$600,000,000'); ?>" target="_blank"><button>Comprar</button></a><?php else: ?><button onclick="abrirModal('login')">Comprar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Grúa')">🏢 Más proveedores</button>
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
            <?php if($error_msg): ?><div class="error-msg"><?php echo htmlspecialchars($error_msg); ?></div><?php endif; ?>
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

<!-- MODAL PROVEEDORES -->
<div id="modalProveedores">
    <div class="prov-content">
        <span class="prov-close" onclick="cerrarProveedores()">&times;</span>
        <div class="prov-titulo" id="provTitulo"></div>
        <div class="prov-subtitulo">Otros proveedores disponibles</div>
        <div class="prov-lista" id="provLista"></div>
    </div>
</div>

<script>
const logueado = <?php echo $logueado ? 'true' : 'false'; ?>;
const usuario  = <?php echo $logueado ? json_encode($_SESSION['usuario']) : '"cliente"'; ?>;

const datosProveedores = {
    "Excavadora": [
        { nombre:"Conconcreto S.A. (Colombia)",           precio:"$490,000,000", badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", precio:"$415,000,000", badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   precio:"$460,000,000", badge:"badge-medio", label:"Precio medio"  }
    ],
    "Retroexcavadora": [
        { nombre:"Conconcreto S.A. (Colombia)",           precio:"$345,000,000", badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", precio:"$295,000,000", badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   precio:"$310,000,000", badge:"badge-medio", label:"Precio medio"  }
    ],
    "Cargador Frontal": [
        { nombre:"Conconcreto S.A. (Colombia)",           precio:"$305,000,000", badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", precio:"$255,000,000", badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   precio:"$272,000,000", badge:"badge-medio", label:"Precio medio"  }
    ],
    "Grúa": [
        { nombre:"Conconcreto S.A. (Colombia)",           precio:"$645,000,000", badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", precio:"$565,000,000", badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   precio:"$615,000,000", badge:"badge-medio", label:"Precio medio"  }
    ]
};

function verProveedores(producto) {
    const datos = datosProveedores[producto];
    if (!datos) return;

    document.getElementById("provTitulo").textContent = producto + " — Comparar proveedores";
    const lista = document.getElementById("provLista");
    lista.innerHTML = "";

    datos.forEach(p => {
        let accion;
        if (logueado) {
            const msg = encodeURIComponent("Hola, soy " + usuario + " y estoy interesado en comprar " + producto + " con " + p.nombre + " a un precio de " + p.precio + ".");
            accion = `<a href="https://api.whatsapp.com/send?phone=573216185400&text=${msg}" target="_blank"><button class="prov-btn">Comprar</button></a>`;
        } else {
            accion = `<button class="prov-btn" onclick="cerrarProveedores();abrirModal('login')">Comprar</button>`;
        }

        lista.innerHTML += `
        <div class="prov-card">
            <div class="prov-info">
                <div class="prov-nombre">${p.nombre}</div>
                <div class="prov-precio">${p.precio} &nbsp;<span class="prov-badge ${p.badge}">${p.label}</span></div>
            </div>
            ${accion}
        </div>`;
    });

    const modal = document.getElementById("modalProveedores");
    modal.style.display = "block";
    setTimeout(() => modal.classList.add("show"), 10);
}

function cerrarProveedores() {
    const modal = document.getElementById("modalProveedores");
    modal.classList.remove("show");
    setTimeout(() => { modal.style.display = "none"; }, 300);
}

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
window.onclick = function(e) {
    if (e.target == document.getElementById("modalLogin"))      cerrarModal();
    if (e.target == document.getElementById("modalProveedores")) cerrarProveedores();
}
</script>
</body>
</html>
