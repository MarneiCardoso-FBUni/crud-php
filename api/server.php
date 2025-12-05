<?php
// Importa as configurações do DB MySQL
require_once './db.php';

// Recebe os dados do formulário
$nome  = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Criptografa a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Carrega a Conexão
$conexao = abrirConexao();

// Prepara e executa o INSERT
$sql = "INSERT INTO usuarios (nome, email, senha)
        VALUES (?, ?, ?);";

// Prepara o SQL para executar com segurança (homologação)
$statement = mysqli_prepare($conexao, $sql);

// Liga (bind) os valores reais aos "?" definidos na query SQL
mysqli_stmt_bind_param($statement, "sss", $nome, $email, $senhaHash);
// "sss" informa os tipos de dados que vamos passar:
//     s = string
//     i = inteiro
//     d = decimal (double)
//     b = blob (dados binários)

// Se cadastrou, redireciona para Home
if (mysqli_stmt_execute($statement)) {
    header("Location: ../index.php");
    exit();

} else {
    echo "Erro ao cadastrar usuário: " . mysqli_error($conexao);
}

mysqli_stmt_close($statement);
mysqli_close($conexao);
