<?php

$usuario = 'root';
$senha = '';
$database = 'dbeasykcal';
$host = 'localhost';

$mysqli = new mysqli($host, $usuario, $senha, $database, 3316);

if($mysqli->error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}