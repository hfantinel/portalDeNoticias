<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
include_once './config/config.php';
include_once './classes/Usuario.php';

//Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario = new Usuario($db);

//Processar exlusão do usuário
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    $usuario->deletar($id);
    header('Location: crudUsuario.php');
    exit();
}

// Obter parâmetros de pesquisa e filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '';

// Obter dados dos usuários com filtros
$dados = $usuario->ler($search, $order_by);


//Obtem dados do usuário logado
$dados_Usuario = $usuario->lerPorId($_SESSION['usuario_id']);
$nome_usuario = $dados_Usuario['nome'];

//Obter dados dos usuários
$dados = $usuario->ler($search, $order_by);
//Função para determinar saudação
function saudacao()
{
    $hora = date("H");
    if ($hora >= 6 && $hora < 12) {
        return "Bom dia!";
    } else if ($hora >= 12 && $hora < 18) {
        return "Boa tarde!";
    } else {
        return "Boa noite!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./crudUsuario.css">
    <title>Portal</title>
</head>

<body>

    <div class="botoes">
        <a href="login.php"><button class="voltar">Logout</button></a>
        <a href="portal.php"><button class="voltar">Voltar ao Portal</button></a>
        <a href="registrar.php"><button class="voltar">Adicionar Usuário</button></a>
    </div>
    
    <div class="box">
        <div class="titulo">
            <h1><?php echo saudacao() . "  " . $nome_usuario; ?></h1>
            <h1>Bem Vindo ao Portal de Notícias</h1>
        </div>

        <form method="GET">
            <input class="campTextPesq" type="text" name="search" placeholder="Pesquisar por nome ou email"
                value="<?php echo htmlspecialchars($search); ?>">
            <label class="filtro">
                <input type="radio" name="order_by" value="" <?php if ($order_by == '')
                    echo 'checked'; ?>> Normal
            </label>
            <label class="filtro">
                <input type="radio" name="order_by" value="nome" <?php if ($order_by == 'nome')
                    echo 'checked'; ?>>
                Ordem Alfabética
            </label>
            <label class="filtro">
                <input type="radio" name="order_by" value="sexo" <?php if ($order_by == 'sexo')
                    echo 'checked'; ?>>
                Sexo
            </label>
            <button class="botaoPesq" type="submit">Pesquisar</button>
        </form>



        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Sexo</th>
                <th>Fone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $dados->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['nome'] ?></td>
                    <td><?php echo ($row['sexo'] === 'M') ? 'Masculino' : 'Feminino'; ?></td>
                    <td><?php echo $row['fone'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td class="acoes">
                        <a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a>
                        <a href="deletar.php?id=<?php echo $row['id']; ?>">Deletar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>