<?php
session_start();

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['idusuario'])) {
    die("Você não pode acessar esta página porque não está logado.<p><a href=\"index.php\">Entrar</a></p>");
}

?>