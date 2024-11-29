<?php
class Noticias{
    private $conn;
    private $table_name = "noticias";


    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function salvarNoticia($titulo, $fkusuario, $data, $conteudo, $imagem)
    {
        $query = "INSERT INTO " . $this->table_name . " (titulo, fkusuario, data, conteudo, imagem) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $fkusuario, $data, $conteudo, $imagem]);
        return $stmt;
    }

    public function lerNoticia()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function lerPorIdNoticia($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function atualizarNoticia($id, $titulo, $autor, $conteudo, $imagem, $data_publicacao)
    {
        $query = "UPDATE " . $this->table_name . " SET titulo = ?, fkusuario = ?, conteudo = ?, imagem = ?, data = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $autor, $conteudo, $imagem, $data_publicacao, $id]);
        return $stmt;
    }


    public function deletarNoticia($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt;
    }

    public function listarNoticias(){
        $sql = "SELECT * FROM noticias";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>