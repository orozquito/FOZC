// Esperar a que cargue la página
document.addEventListener("DOMContentLoaded", () => {

    // =========================
    // BOTÓN CONTACTO
    // =========================
    const btn = document.getElementById("btnContacto");

    if (btn) {
        btn.addEventListener("click", () => {
            alert("Gracias por contactar con FOZC");
        });
    }

    // =========================
    // TARJETAS
    // =========================
    const alquilerCard = document.getElementById("alquiler");
    if (alquilerCard) {
        alquilerCard.addEventListener("click", () => {
            window.location.href = "pages/alquiler.php";
        });
    }

    const compraCard = document.getElementById("compra");
    if (compraCard) {
        compraCard.addEventListener("click", () => {
            window.location.href = "pages/compra.php";
        });
    }

    const servicioCard = document.getElementById("servicio");
    if (servicioCard) {
        servicioCard.addEventListener("click", () => {
            window.location.href = "pages/servicios.php";
        });
    }

    // =========================
    // ANIMACIONES CARDS
    // =========================
    const cards = document.querySelectorAll('.maquinaria-grid .card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

});