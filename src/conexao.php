<?php

$usuario = 'root';
$senha = '';
$database = 'dbeasykcal';
$host = 'localhost';
$porta = 3316;

$conn = new mysqli($host, $usuario, $senha, $database, $porta);

if($conn->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}