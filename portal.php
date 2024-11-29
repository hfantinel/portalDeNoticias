<?php

session_start();
date_default_timezone_set('America/Sao_Paulo');
include_once './config/config.php';
include_once './classes/Usuario.php';

$usuario = new Usuario($db);

//Obtem dados do usuário logado
$dados_Usuario = $usuario->lerPorId($_SESSION['usuario_id']);
$nome_usuario = $dados_Usuario['nome'];

//Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

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
    <link rel="stylesheet" href="./portal.css">
    <title>Portal</title>
</head>

<body>

    <div class="header">
        <a href="login.php"><button class="botao">Logout</button></a>
    </div>

    <div class="box">
        <div class="titulo">
            <h1><?php echo saudacao() . "  " . $nome_usuario; ?></h1>
            <h1>Bem Vindo ao Portal de Notícias</h1>
            <p> Escolha uma das opções abaixo:</p>
        </div>

        <div class="botoes">
            <a href="crudUsuario.php"><button class="botao">Usuários</button></a><br><br>
            <a href="crudNoticias.php"><button class="botao">Notícias</button></a><br><br>
        </div>
    </div>

</body>

</html>