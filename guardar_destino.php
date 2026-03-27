<?php
session_start();

if (isset($_POST['destino'])) {
    $_SESSION['redirect'] = $_POST['destino'];
}
?>