<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
include_once './config/config.php';
include_once './classes/Usuario.php';
include_once './classes/Noticias.php';


if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$n = new Noticias($db);


if (isset($_GET['idnot'])) {
    $idnot = $_GET['idnot'];
    $noticias->deletarNot($idnot);
    header('Location: crudNoticias.php');
    exit();
}


$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '';


$dados = $n->lerNoticia($search, $order_by);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="crudNoticias.css">
    <title>crudNotícias</title>
</head>

<body>

    <div class="botoesSaida">
    <a href="login.php"><button class="botao">Logout</button></a>
    <a href="portal.php"><button class="botao">Voltar ao Portal</button></a>
    </div>

    <div class="box">
        <div class="titulo">
            <h1>Portal de Notícias.</h1>
        </div>

        <form method="POST" action="">
            <input class="campTextPesq" type="text" name="search" placeholder="Pesquisar por nome ou título"
                value="<?php echo htmlspecialchars($search); ?>">
            <label class="filtro">
                <input type="radio" name="order_by" value="" <?php if ($order_by == '')
                    echo 'checked'; ?>> Normal
            </label>
            <label class="filtro">
                <input type="radio" name="order_by" value="nome" <?php if ($order_by == 'titulo')
                    echo 'checked'; ?>>
                Ordem Alfabética
            </label>
            <label class="filtro">
                <input type="radio" name="order_by" value="sexo" <?php if ($order_by == 'data')
                    echo 'checked'; ?>>
                Por data
            </label>
            <button type="submit" class="botaoPesq">Pesquisar</button>
        </form>

        <table border="1">
            <tr>
                <th>Usuário</th>
                <th>Título</th>
                <th>Notícias</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $dados->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['fkusuario'] ?></td>
                    <td><?php echo $row['titulo'] ?></td>
                    <td><?php echo $row['conteudo'] ?></td>
                    <td><?php echo $row['data'] ?></td>
                    <td class="acoes">

                        <a href="editarNot.php?idnot=<?php echo $row['id']; ?>">Editar</a>

                        <a href="deletarNot.php?idnot=<?php echo $row['id']; ?>">Deletar</a>

                    </td>
                </tr>

            <?php endwhile ?>
        </table>

        <div class="registrarNot">
            <a href="registrarNot.php"><button class="botao">Registre sua Notícia</button></a>
        </div>

    </div>

</body>

</html>