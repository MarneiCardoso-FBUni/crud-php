# CRUD em PHP e MySQL

## Estrutura de Pastas

```
/crud/
├── api/
│   ├── db.php      ← Contém a conexão MySQL
│   └── server.php  ← Recebe os dados e insere no DB
├── css/
│   └── style.css   ← Estilo da aplicação
├── cadastro.html   ← Formulário de cadastro
└── index.php       ← Home - listar usuários
```

## 1. Criar a Base de Dados MySQL

```sql
CREATE DATABASE IF NOT EXISTS crud_php;
USE crud_php;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 2. Configuração do Banco (api/db.php)

```php
<?php
function abrirConexao()
{
    $host = "localhost";
    $user = "root";
    $pass = "1234";
    $db   = "crud_php";

    $conexao = mysqli_connect($host, $user, $pass, $db);

    if (!$conexao) {
        die("Erro na conexão com o banco: " . mysqli_connect_error());
    }

    return $conexao;
}
```

## 3. Criar a lógica de inserção (api/server.php)

```php
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
```

## 4. Formulário de Cadastro (cadastro.html)

```html
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD com PHP e MySQL</title>
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header>
        <h1>CRUD com PHP e MySQL</h1>
    </header>

    <main>
        <form action="./api/server.php" method="post">
            <h2>Cadastrar Usuários</h2>
            
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Informe seu nome completo">

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com">

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Informe sua senha">

            <button type="submit">Cadastrar</button>
        </form>
    </main>
</body>
</html>
```

## 5. Criar a Tela Home (index.html)

```php
<?php
require_once './api/db.php';
$conexao = abrirConexao();

$sql = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($resultado);
?>
```

```html
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD com PHP e MySQL</title>
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header>
        <h1>CRUD com PHP e MySQL</h1>

        <a href="cadastro.html" class="btn">Cadastrar</a>
    </header>

    <main>
        <div id="listaUsuarios">
            <?php if ($total > 0): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Criação</th>
                    </tr>

                    <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?= $usuario['id'] ?></td>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td><?= $usuario['criado_em'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <h3>Nenhum usuário cadastrado.</h3>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
```

```css
/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Verdana, sans-serif;
}

body {
    background-color: #b5bbc5;
    color: #232b38;
}

header {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6rem;
    padding: 2rem;
    background-color: #3b35b9;
    color: #e8ebef;
}

main {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem 4rem;
}

form {
    background-color: #e8ebef;
    padding: 2rem 3rem;
    border-radius: 0.5rem;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 30rem;

    input,
    textarea,
    button {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
        padding: 0.5rem;
        border: 0.1rem solid #b5bbc5;
        border-radius: 0.3rem;
        font-size: 1rem;
    }
}

h2 {
    margin-bottom: 1rem;
}

button, .btn {
    background-color: #3b35b9;
    color: #e8ebef;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    letter-spacing: 0.1rem;

    &:hover {
        filter: brightness(1.5);
    }
}

.btn {
    background-color: #6171ff;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-size: 1.2rem;
}

table {
    width: 50rem;
}

table, th, td {
    background-color: #e8ebef;
    border: 0.05rem solid #b5bbc5;
    border-collapse: collapse;
    padding: 0.5rem;
    text-align: center;
}
```