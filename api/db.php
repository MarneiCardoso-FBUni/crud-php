<?php
function abrirConexao()
{
    $host = "localhost";
    $user = "root";
    $pass = "usbw";
    $db   = "crud_php";

    $conexao = mysqli_connect($host, $user, $pass, $db);

    if (!$conexao) {
        die("Erro na conexão com o banco: " . mysqli_connect_error());
    }

    return $conexao;
}
