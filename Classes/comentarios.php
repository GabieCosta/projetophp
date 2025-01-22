<?php
require_once 'includes/database.php';

class Comentario
{
    private $id;
    private $curso_id;
    private $usuario_id;
    private $comentario;
    private $created_at;

    // Construtor
    public function __construct($id = null, $curso_id, $usuario_id, $comentario, $created_at = null)
    {
        $this->id = $id;
        $this->curso_id = $curso_id;
        $this->usuario_id = $usuario_id;
        $this->comentario = $comentario;
        $this->created_at = $created_at ?? date("Y-m-d H:i:s");
    }

    // Método para inserir um novo comentário
    public function comentar()
    {
        if (empty($this->curso_id) || empty($this->usuario_id) || empty($this->comentario)) {
            throw new Exception("Todos os atributos são obrigatórios.");
        }

        $database = new Database();
        $pdo = $database->connect();
        $sql = "INSERT INTO comentarios (curso_id, usuario_id, comentario, created_at) 
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->curso_id,
            $this->usuario_id,
            $this->comentario,
            $this->created_at
        ]);
    }

    // Método para atualizar o conteúdo de um comentário
    public function atualiza_comentario()
    {
        if (empty($this->id) || empty($this->comentario)) {
            throw new Exception("ID do comentário e novo conteúdo são obrigatórios.");
        }

        $database = new Database();
        $pdo = $database->connect();
        $sql = "UPDATE comentarios SET comentario = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->comentario,
            $this->id
        ]);
    }

    // Método para excluir um comentário
    public function excluir_comentario()
    {
        if (empty($this->id)) {
            throw new Exception("ID do comentário é obrigatório.");
        }

        $database = new Database();
        $pdo = $database->connect();
        $sql = "DELETE FROM comentarios WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
    }


    // Método para pegar todos os comentários de um curso
    public static function pegar_comentarios_por_curso($curso_id)
    {
        if (empty($curso_id)) {
            throw new Exception("ID do curso é obrigatório.");
        }

        $database = new Database();
        $pdo = $database->connect();
        $sql = "SELECT * FROM comentarios WHERE curso_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$curso_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getters e setters
    public function getId()
    {
        return $this->id;
    }

    public function getCursoId()
    {
        return $this->curso_id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function getComentario()
    {
        return $this->comentario;
    }

    public function getDataCriacao()
    {
        return $this->created_at;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCursoId($curso_id)
    {
        $this->curso_id = $curso_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    public function setDataCriacao($data_criacao)
    {
        $this->created_at = $data_criacao;
    }
}
?>