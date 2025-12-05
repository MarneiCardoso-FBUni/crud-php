<?php
require_once './api/db.php';
$conexao = abrirConexao();

$sql = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($resultado);
?>

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
