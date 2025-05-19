<?php

$usuario = 'root';
$senha = '';
$database = 'dbeasykcal';
$host = 'localhost';

$conn = new mysqli($host, $usuario, $senha, $database, 3316);

if($conn->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}