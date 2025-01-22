<?php
require_once 'includes/database.php'; 

class Curso {
    private $id;
    private $titulo;
    private $resumo;
    private $descricao;
    private $area_estudo;
    private $nivelamento;
    private $imagem;
    private $instrutor_id;
    private $data_criacao;

    // Construtor

    public function __construct(
        $id = null,
        $titulo = '',
        $resumo = '',
        $descricao = '',
        $area_estudo = '',
        $nivelamento = '',
        $imagem = '',
        $instrutor_id = null
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->resumo = $resumo;
        $this->descricao = $descricao;
        $this->area_estudo = $area_estudo;
        $this->nivelamento = $nivelamento;
        $this->imagem = $imagem;
        $this->instrutor_id = $instrutor_id;
    }

    // Método para criar um novo curso
    public function cria_curso() {
        if (empty($this->titulo) || empty($this->resumo) || empty($this->imagem) || empty($this->area_estudo) || empty($this->nivelamento)) {
            throw new Exception("Título, resumo, imagem, área de estudo e nivelamento são obrigatórios.");
        }

        $database = new Database();
        $pdo = $database->connect();

        $sql = "INSERT INTO cursos (titulo, resumo, descricao, area_estudo, nivelamento, imagem, instrutor_id, data_criacao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->titulo,
            $this->resumo,
            $this->descricao,
            $this->area_estudo,
            $this->nivelamento,
            $this->imagem,
            $this->instrutor_id,
            $this->data_criacao
        ]);
    }

    // Método para atualizar os dados de um curso
    public function atualiza_curso() {
        // Atualiza apenas resumo, imagem e título
        $database = new Database();
        $pdo = $database->connect();
        $sql = "UPDATE cursos SET titulo = ?, resumo = ?, imagem = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->titulo,
            $this->resumo,
            $this->imagem,
            $this->id
        ]);
    }

    // Método para excluir um curso e seus comentários
    public function excluir_curso() {
        $database = new Database();
        $pdo = $database->connect();

        // Exclui os comentários relacionados ao curso
        $sqlComentarios = "DELETE FROM comentarios WHERE curso_id = ?";
        $stmtComentarios = $pdo->prepare($sqlComentarios);
        $stmtComentarios->execute([$this->id]);

        // Exclui o curso
        $sqlCurso = "DELETE FROM cursos WHERE id = ?";
        $stmtCurso = $pdo->prepare($sqlCurso);
        $stmtCurso->execute([$this->id]);
    }
    public function pesquisa($texto = null, $area_estudo = null, $nivelamento = null, $userId = null) {
        $database = new Database();
        $pdo = $database->connect();
        $sql = "SELECT * FROM cursos WHERE 1=1"; // Inicializa a consulta

        $params = [];

        // Adiciona condições baseadas nos parâmetros fornecidos
        if (!empty($texto)) {
            $sql .= " AND (titulo LIKE ? OR resumo LIKE ?)";
            $params[] = "%$texto%";
            $params[] = "%$texto%";
        }

        if (!empty($area_estudo)) {
            $sql .= " AND area_estudo = ?";
            $params[] = $area_estudo;
        }

        if (!empty($nivelamento)) {
            $sql .= " AND nivelamento = ?";
            $params[] = $nivelamento;
        }

        if (!empty($userId)) {
            $sql .= " AND instrutor_id = ?";
            $params[] = $userId;
        }

        $stmt = $pdo->prepare($sql); // Prepara a consulta
        $stmt->execute($params); // Executa com os parâmetros

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os resultados como um array associativo
    }

    // Método para obter um curso pelo ID
    public static function getCursoPorId($id) {
        $database = new Database();
        $pdo = $database->connect();

        $sql = "SELECT * FROM cursos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        $curso = $stmt->fetch(PDO::FETCH_ASSOC);
        return $curso;
    }

    // Getters e setters
    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getResumo() {
        return $this->resumo;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getAreaEstudo() {
        return $this->area_estudo;
    }

    public function getNivelamento() {
        return $this->nivelamento;
    }

    public function getImagem() {
        return $this->imagem;
    }

    public function getInstrutorId() {
        return $this->instrutor_id;
    }

    public function getDataCriacao() {
        return $this->data_criacao;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setResumo($resumo) {
        $this->resumo = $resumo;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setAreaEstudo($area_estudo) {
        $this->area_estudo = $area_estudo;
    }

    public function setNivelamento($nivelamento) {
        $this->nivelamento = $nivelamento;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }

    public function setInstrutorId($instrutor_id) {
        $this->instrutor_id = $instrutor_id;
    }

    public function setDataCriacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }
}
?>
