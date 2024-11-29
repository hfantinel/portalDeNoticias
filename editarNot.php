<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: crudNoticias.php');
    exit();
}

include_once './config/config.php';
include_once './classes/Noticias.php';

$n = new Noticias($db);


if (isset($_GET['idnot'])) {
    $id = $_GET['idnot'];
    $row = $n->lerPorIdNoticia($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idnot = $_POST['idnot'];
    $titulo = $_POST['titulo'];
    $autor =  $_POST['autor'];

    $conteudo = $_POST['noticia'];
    $imagem = $_FILES['imagem'];
    $data_publicacao = date('Y-m-d');
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


    $n->atualizarNoticia($idnot, $titulo, $autor, $conteudo, $nomeImagem, $data_publicacao);
    header('Location: crudNoticias.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia</title>
</head>

<body>
    <div class="box">
        <div class="titulo">
            <h1>Edite sua Notícia</h1>
        </div>
        
        <form method="POST"  enctype="multipart/form-data">
            <input type="hidden" name="idnot" value="<?php echo $row['id']; ?>">
            <label for="titulo">Título: </label>
            <input type="text" name="titulo" value="<?php echo $row['titulo'] ?>" required><br><br>
            <label for="autor">Autor: </label>
            <input type="text" name="autor" value="<?php echo $row['fkusuario'] ?>" required><br><br>
            <label for="noticia">Notícia: </label>
            <input type="text" name="noticia" value="<?php echo $row['conteudo'] ?>" required><br><br>
            <label for="imagem">Imagem</label>
            <input type="file" name="imagem" id="imagem" accept=".jpg,.png" value="<?php echo $row['imagem'] ?>"><br><br>

            <input type="submit" value="atualizar">
        </form>
    </div>
</body>

</html>