<?php
session_start();
include_once './config/config.php';
include_once './classes/Noticias.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = new Noticias($db);
    $idusu =  $_SESSION['usuario_id'];
    $titulo = $_POST['titulo'];
    $noticia = $_POST['noticia'];
    $data = date('Y-m-d');
    $imagem = $_FILES['imagem'];

    // Validações do upload da imagem
    $nomeImagem = "";
    if ($imagem['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
        $tamanhoMaximo = 10 * 1024 * 1024; // 10 MB

        // Validar tipo de arquivo
        $tiposPermitidos = ['jpg', 'jpeg', 'png'];
        if (!in_array($extensao, $tiposPermitidos)) {
            die("Apenas arquivos JPG ou PNG são permitidos.");
        }

        // Validar tamanho do arquivo
        if ($imagem['size'] > $tamanhoMaximo) {
            die("O tamanho do arquivo não pode exceder 10 MB.");
        }

        // Gerar nome único para o arquivo
        $nomeImagem = uniqid() . "." . $extensao;
        $destino = "uploads/" . $nomeImagem;

        // Mover o arquivo para o diretório
        if (!move_uploaded_file($imagem['tmp_name'], $destino)) {
            die("Erro ao salvar a imagem.");
        }
    } else if ($imagem['error'] !== UPLOAD_ERR_NO_FILE) {
        die("Erro ao fazer upload da imagem.");
    }
  

    $n->salvarNoticia($titulo, $idusu, $data, $noticia,  $nomeImagem );
    header('Location: crudNoticias.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./registrarNot.css">
    <title>Registrar Notícia</title>
</head>

<body>
    <a href="crudNoticias.php"><button class="botao">Voltar</button></a>

    <div class="box">
        <div class="titulo">
            <h1>Registre sua Notícia</h1>
        </div>

        <div class="cadastro">
            <form method="POST"  enctype="multipart/form-data">
                <label for="titulo">Título: </label>
                <input type="text" class="campText" name="titulo" required><br><br>
                <label for="noticia">Notícia: </label>
                <input type="text" class="campText" name="noticia" required><br><br>
                <label for="imagem">Imagem</label>
                <input type="file" name="imagem" id="imagem" accept=".jpg,.png">

                <input class="botao" type="submit" value="Adicionar Notícia">
            </form>
        </div>
    </div>
</body>

</html>