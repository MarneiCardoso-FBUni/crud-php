<?php
// Recebe os valores vindos do formulário
$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];

// Cripgrafa a senha
$senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
echo $senha_criptografada;



// echo "Nome: $nome <br>";
// echo "E-mail: $email <br>";
// echo "Senha: $senha <br>";


// echo "Bem-vindo(a) $nome!";


// Pesquisar sobre PSR em PHP
