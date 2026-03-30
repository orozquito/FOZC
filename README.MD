<div align="center">

# 🏗️ FOZC
### Plataforma de Maquinaria Pesada

**Alquiler · Compra · Servicio Técnico**

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-fozc__db-4479A1?style=flat-square&logo=mysql&logoColor=white)
![WhatsApp](https://img.shields.io/badge/Integración-WhatsApp-25D366?style=flat-square&logo=whatsapp&logoColor=white)
![Estado](https://img.shields.io/badge/Estado-Activo-22c55e?style=flat-square)

</div>

---

## 📌 ¿Qué es FOZC?

**FOZC** es una plataforma web para la gestión y comercialización de maquinaria pesada en Colombia. Permite a los clientes explorar el catálogo libremente, comparar proveedores y contactar directamente por WhatsApp para alquilar o comprar equipos. El registro e inicio de sesión se requieren únicamente al momento de realizar una acción de compra o alquiler.

---

## ✨ Características

- 🔓 **Catálogo público** — Navega maquinaria sin necesidad de registrarte
- 🔐 **Autenticación modal** — Login y registro en ventana emergente, sin cambiar de página
- 🏢 **Comparador de proveedores** — Consulta precios de Conconcreto S.A., Mota-Engil Latinoamérica y Cemex Colombia
- 📱 **Integración WhatsApp** — Los botones de acción generan mensajes personalizados con el nombre del usuario
- 🔄 **Redirección inteligente** — Tras el login, el usuario regresa exactamente a donde estaba
- 🎨 **Diseño glassmorphism** — Estilo transparente con fondos de maquinaria real

---

## 🗂️ Estructura del proyecto
```
FOZC/
├── index.php                  # Página principal con modal de login
├── procesar_login.php         # Procesamiento de inicio de sesión
├── guardar_usuarios.php       # Registro de nuevos usuarios
├── logout.php                 # Cierre de sesión
├── setup_db.php               # ⚙️ Instalador de base de datos (ejecutar una vez)
│
├── pages/
│   ├── alquiler.php           # Catálogo de alquiler + comparador proveedores
│   ├── compra.php             # Catálogo de compra + comparador proveedores
│   └── servicios.php          # Servicios técnicos
│
├── config/
│   └── db.php                 # Configuración de conexión MySQL
│
├── css/
│   └── styles.css             # Estilos globales (glassmorphism)
│
└── img/
    ├── portada.jpeg
    ├── portada-alquiler.jpeg
    ├── fondo-compra.jpeg
    └── fondo-servicios.jpeg
```

---

## 🚀 Instalación

### 1. Requisitos

| Requisito | Versión mínima |
|-----------|---------------|
| PHP | 7.4+ |
| MySQL | 5.7+ |
| Servidor | Apache / XAMPP / Laragon |

### 2. Clonar o copiar el proyecto
```bash
# Copia la carpeta FOZC dentro de tu htdocs o www
cp -r FOZC/ /xampp/htdocs/FOZC
```

### 3. Crear la base de datos

Abre tu navegador y visita:
```
http://localhost/FOZC/setup_db.php
```

Esto crea automáticamente la base de datos `fozc_db` y la tabla `usuarios`. Solo necesitas hacerlo **una vez**.

### 4. Configurar la conexión (si es necesario)

Edita `config/db.php` si tus credenciales de MySQL son diferentes:
```php
$host = "localhost";
$user = "root";
$pass = "";          // Tu contraseña de MySQL
$db   = "fozc_db";
```

### 5. ¡Listo!
```
http://localhost/FOZC/
```

---

## 🧭 Flujo de usuario
```
Página principal
      │
      ├── Ver catálogo (sin login requerido)
      │         │
      │    ┌────┴────┐
      │  Alquiler  Compra  Servicios
      │         │
      │    Ver productos y precios
      │         │
      │    Clic en "Comprar" / "Alquilar"
      │         │
      │    ¿Está logueado?
      │       /     \
      │     SÍ       NO
      │      │        │
      │   WhatsApp   Modal Login
      │              │
      │         Inicia sesión
      │              │
      │         Regresa a la misma página
      │              │
      │           WhatsApp ✅
```

---

## 🏢 Proveedores disponibles

En cada producto de **Compra** y **Alquiler** aparece el botón **"Más proveedores"** que despliega una comparativa de precios:

| Proveedor | Tipo de precio |
|-----------|---------------|
| 🔴 Conconcreto S.A. (Colombia) | Precio alto |
| 🟢 Constructora Mota-Engil Latinoamérica | Más económico |
| 🟡 Cemex Colombia S.A. | Precio medio |

---

## 🛠️ Maquinaria disponible

**Alquiler** (tarifas mensual / diario / por hora):
> Excavadora · Retroexcavadora · Cargador Frontal · Grúa · Compactador · Generador Eléctrico

**Compra** (precio por unidad):
> Excavadora · Retroexcavadora · Cargador Frontal · Grúa

**Servicios Técnicos:**
> Mantenimiento · Transporte · Asesoría Técnica · Operadores

---

## 📞 Contacto y ventas

Todas las acciones de compra y alquiler generan un mensaje personalizado directo a:

**WhatsApp:** [`+57 321 618 5400`](https://api.whatsapp.com/send?phone=573216185400)

---

## 📄 Licencia

© 2026 FOZC — Todos los derechos reservados.