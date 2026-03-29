<?php
session_start();

$error_msg = "";
if (isset($_GET['login_error']) && isset($_SESSION['error_login'])) {
    $error_msg = $_SESSION['error_login'];
    unset($_SESSION['error_login']);
}

function generarWhatsApp($producto, $tipo) {
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "cliente";
    $mensaje = "Hola, soy $usuario y estoy interesado en alquilar $producto. Me gustaría recibir información sobre tarifas de $tipo y disponibilidad.";
    return "https://api.whatsapp.com/send?phone=573216185400&text=" . urlencode($mensaje);
}

$logueado = isset($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alquiler - FOZC</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
    .modal { display:none; position:fixed; z-index:100; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.7); }
    .modal-content { background:white; width:320px; padding:25px; border-radius:15px; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) scale(0.8); opacity:0; transition:0.3s; }
    .modal.show .modal-content { transform:translate(-50%,-50%) scale(1); opacity:1; }
    .modal-content input { width:100%; padding:10px; margin:10px 0; box-sizing:border-box; }
    .modal-content button { width:100%; padding:10px; background:transparent; border:2px solid #2b2d31; color:#2b2d31; border-radius:25px; cursor:pointer; transition:0.3s; margin-top:8px; }
    .modal-content button:hover { background:#000000; color:white; }
    .close { float:right; cursor:pointer; font-size:20px; line-height:1; }
    .switch { margin-top:10px; font-size:14px; cursor:pointer; color:blue; text-align:center; }
    .error-msg { background:#fee2e2; color:#b91c1c; border-radius:8px; padding:8px 12px; margin-bottom:10px; font-size:14px; text-align:center; }
    .user-panel { display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:8px; }
    .btn-logout { background:transparent; border:1px solid white; color:black; padding:6px 16px; border-radius:20px; cursor:pointer; font-size:14px; text-decoration:none; transition:0.3s; display:inline-block; width:auto; margin:0; }
    .btn-logout:hover { background:black; color:white; }

    .btn-proveedores { width:100%; padding:7px 10px; margin-top:6px; background:transparent; border:1px solid rgba(0,0,0,0.35); color:#000; border-radius:10px; cursor:pointer; font-size:0.78rem; font-weight:600; letter-spacing:0.03em; transition:0.25s; display:block; }
    .btn-proveedores:hover { background:rgba(0,0,0,0.08); border-color:rgba(0,0,0,0.6); transform:translateY(-1px); }

    #modalProveedores { display:none; position:fixed; z-index:200; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.75); }
    .prov-content { background:white; width:min(480px,92%); padding:28px; border-radius:18px; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) scale(0.85); opacity:0; transition:0.3s; max-height:85vh; overflow-y:auto; }
    #modalProveedores.show .prov-content { transform:translate(-50%,-50%) scale(1); opacity:1; }
    .prov-titulo { font-size:1.1rem; font-weight:700; margin-bottom:4px; }
    .prov-subtitulo { font-size:0.85rem; color:#555; margin-bottom:18px; }
    .prov-lista { display:flex; flex-direction:column; gap:12px; }
    .prov-card { border:1px solid rgba(0,0,0,0.15); border-radius:12px; padding:14px 16px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; transition:0.2s; }
    .prov-card:hover { background:rgba(0,0,0,0.03); border-color:rgba(0,0,0,0.3); }
    .prov-info { flex:1; }
    .prov-nombre { font-weight:700; font-size:0.95rem; margin-bottom:4px; }
    .prov-tarifas { font-size:0.82rem; color:#0d6e3a; font-weight:600; line-height:1.7; }
    .prov-badge { font-size:0.7rem; padding:2px 8px; border-radius:20px; font-weight:700; white-space:nowrap; display:inline-block; margin-top:4px; }
    .badge-caro  { background:#fee2e2; color:#b91c1c; }
    .badge-medio { background:#fef3c7; color:#92400e; }
    .badge-eco   { background:#dcfce7; color:#15803d; }
    .prov-btn { padding:7px 16px; border-radius:20px; border:none; background:#0d3e26; color:white; cursor:pointer; font-size:0.82rem; font-weight:600; transition:0.25s; width:auto; margin:0; display:inline-block; white-space:nowrap; align-self:center; }
    .prov-btn:hover { background:#145c38; transform:translateY(-1px); }
    .prov-close { float:right; cursor:pointer; font-size:20px; line-height:1; }
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
        <button class="btn-auth" style="background:transparent;border:1px solid white;color:black;padding:8px 20px;border-radius:20px;cursor:pointer;font-size:15px;font-weight:400;transition:0.3s;" onclick="abrirModal('login')">INICIAR SESIÓN</button>
        <button class="btn-auth" style="background:transparent;border:1px solid white;color:black;padding:8px 20px;border-radius:20px;cursor:pointer;font-size:15px;font-weight:400;transition:0.3s;" onclick="abrirModal('registro')">REGISTRARSE</button>
    </div>
<?php endif; ?>
    <h1>FOZC</h1>
</header>

<a href="../index.php" class="back-button">←</a>

<section class="alquiler-section alquiler-bg">
    <h2>Maquinaria en Alquiler</h2>
    <p>Descubre nuestra selección de maquinaria pesada disponible para alquiler.</p>
    <div class="maquinaria-grid">

        <div class="card">
            <h3>Excavadora</h3>
            <p><strong>Mensual:</strong> $15,000,000</p>
            <p><strong>Diario:</strong> $800,000</p>
            <p><strong>Hora:</strong> $100,000</p>
            <p>Ideal para excavaciones y movimientos de tierra.</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Excavadora','mensual, diaria o por horas'); ?>" target="_blank"><button>Alquilar</button></a><?php else: ?><button onclick="abrirModal('login')">Alquilar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Excavadora')">🏢 Más proveedores</button>
        </div>

        <div class="card">
            <h3>Retroexcavadora</h3>
            <p><strong>Mensual:</strong> $12,000,000</p>
            <p><strong>Diario:</strong> $600,000</p>
            <p><strong>Hora:</strong> $75,000</p>
            <p>Versátil para trabajos de carga y excavación.</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Retroexcavadora','mensual, diaria o por horas'); ?>" target="_blank"><button>Alquilar</button></a><?php else: ?><button onclick="abrirModal('login')">Alquilar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Retroexcavadora')">🏢 Más proveedores</button>
        </div>

        <div class="card">
            <h3>Cargador Frontal</h3>
            <p><strong>Mensual:</strong> $10,000,000</p>
            <p><strong>Diario:</strong> $500,000</p>
            <p><strong>Hora:</strong> $62,500</p>
            <p>Perfecto para carga y transporte de materiales.</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Cargador Frontal','mensual, diaria o por horas'); ?>" target="_blank"><button>Alquilar</button></a><?php else: ?><button onclick="abrirModal('login')">Alquilar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Cargador Frontal')">🏢 Más proveedores</button>
        </div>

        <div class="card">
            <h3>Grúa</h3>
            <p><strong>Mensual:</strong> $25,000,000</p>
            <p><strong>Diario:</strong> $1,200,000</p>
            <p><strong>Hora:</strong> $150,000</p>
            <p>Para levantamiento de cargas pesadas.</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Grúa','mensual, diaria o por horas'); ?>" target="_blank"><button>Alquilar</button></a><?php else: ?><button onclick="abrirModal('login')">Alquilar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Grúa')">🏢 Más proveedores</button>
        </div>

        <div class="card">
            <h3>Compactador</h3>
            <p><strong>Mensual:</strong> $8,000,000</p>
            <p><strong>Diario:</strong> $400,000</p>
            <p><strong>Hora:</strong> $50,000</p>
            <p>Para compactación de suelos.</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Compactador','mensual, diaria o por horas'); ?>" target="_blank"><button>Alquilar</button></a><?php else: ?><button onclick="abrirModal('login')">Alquilar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Compactador')">🏢 Más proveedores</button>
        </div>

        <div class="card">
            <h3>Generador Eléctrico</h3>
            <p><strong>Mensual:</strong> $4,000,000</p>
            <p><strong>Diario:</strong> $200,000</p>
            <p><strong>Hora:</strong> $25,000</p>
            <p>Energía confiable en cualquier sitio.</p>
            <?php if($logueado): ?><a href="<?php echo generarWhatsApp('Generador Eléctrico','mensual, diaria o por horas'); ?>" target="_blank"><button>Alquilar</button></a><?php else: ?><button onclick="abrirModal('login')">Alquilar</button><?php endif; ?>
            <button class="btn-proveedores" onclick="verProveedores('Generador Eléctrico')">🏢 Más proveedores</button>
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
                <input type="hidden" name="redirect" value="pages/alquiler.php">
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
            </form>
            <div class="switch" onclick="cambiarFormulario('registro')">¿No tienes cuenta? Regístrate</div>
        </div>
        <div id="formRegistro" style="display:none;">
            <h2>Registro</h2>
            <form action="../guardar_usuarios.php" method="POST">
                <input type="hidden" name="redirect" value="pages/alquiler.php">
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

/* Tarifas por proveedor para alquiler: mensual / diario / hora */
const datosProveedores = {
    "Excavadora": [
        { nombre:"Conconcreto S.A. (Colombia)",           mensual:"$17,500,000", diario:"$920,000",   hora:"$118,000", badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", mensual:"$13,200,000", diario:"$710,000",   hora:"$88,000",  badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   mensual:"$15,800,000", diario:"$840,000",   hora:"$105,000", badge:"badge-medio", label:"Precio medio"  }
    ],
    "Retroexcavadora": [
        { nombre:"Conconcreto S.A. (Colombia)",           mensual:"$13,500,000", diario:"$680,000",   hora:"$85,000",  badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", mensual:"$10,800,000", diario:"$545,000",   hora:"$68,000",  badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   mensual:"$12,200,000", diario:"$620,000",   hora:"$77,000",  badge:"badge-medio", label:"Precio medio"  }
    ],
    "Cargador Frontal": [
        { nombre:"Conconcreto S.A. (Colombia)",           mensual:"$11,500,000", diario:"$575,000",   hora:"$72,000",  badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", mensual:"$8,900,000",  diario:"$450,000",   hora:"$56,000",  badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   mensual:"$10,200,000", diario:"$515,000",   hora:"$64,000",  badge:"badge-medio", label:"Precio medio"  }
    ],
    "Grúa": [
        { nombre:"Conconcreto S.A. (Colombia)",           mensual:"$28,000,000", diario:"$1,380,000", hora:"$172,000", badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", mensual:"$22,500,000", diario:"$1,080,000", hora:"$135,000", badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   mensual:"$25,500,000", diario:"$1,250,000", hora:"$156,000", badge:"badge-medio", label:"Precio medio"  }
    ],
    "Compactador": [
        { nombre:"Conconcreto S.A. (Colombia)",           mensual:"$9,200,000",  diario:"$460,000",   hora:"$58,000",  badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", mensual:"$7,100,000",  diario:"$355,000",   hora:"$44,000",  badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   mensual:"$8,300,000",  diario:"$415,000",   hora:"$52,000",  badge:"badge-medio", label:"Precio medio"  }
    ],
    "Generador Eléctrico": [
        { nombre:"Conconcreto S.A. (Colombia)",           mensual:"$4,600,000",  diario:"$230,000",   hora:"$29,000",  badge:"badge-caro",  label:"Precio alto"   },
        { nombre:"Constructora Mota-Engil Latinoamérica", mensual:"$3,500,000",  diario:"$175,000",   hora:"$22,000",  badge:"badge-eco",   label:"Más económico" },
        { nombre:"Cemex Colombia S.A.",                   mensual:"$4,100,000",  diario:"$205,000",   hora:"$26,000",  badge:"badge-medio", label:"Precio medio"  }
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
            const msg = encodeURIComponent("Hola, soy " + usuario + " y estoy interesado en alquilar " + producto + " con " + p.nombre + ". Tarifas: Mensual " + p.mensual + " / Diario " + p.diario + " / Hora " + p.hora + ".");
            accion = `<a href="https://api.whatsapp.com/send?phone=573216185400&text=${msg}" target="_blank"><button class="prov-btn">Alquilar</button></a>`;
        } else {
            accion = `<button class="prov-btn" onclick="cerrarProveedores();abrirModal('login')">Alquilar</button>`;
        }

        lista.innerHTML += `
        <div class="prov-card">
            <div class="prov-info">
                <div class="prov-nombre">${p.nombre} <span class="prov-badge ${p.badge}">${p.label}</span></div>
                <div class="prov-tarifas">
                    📅 Mensual: ${p.mensual}<br>
                    📆 Diario: ${p.diario}<br>
                    ⏱ Hora: ${p.hora}
                </div>
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
    if (e.target == document.getElementById("modalLogin"))       cerrarModal();
    if (e.target == document.getElementById("modalProveedores")) cerrarProveedores();
}
</script>
</body>
</html>
