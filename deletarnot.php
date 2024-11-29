<?php 

    include_once './config/config.php';
    include_once './classes/Noticias.php';
    session_start();
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php');
        exit();
    }

   

    $noticia = new Noticias($db);
    if(isset($_GET['idnot'])){
        $id = $_GET['idnot'];
        $noticia->deletarNoticia($id);
        header('Location: crudNoticias.php');
        exit();
    }
?>