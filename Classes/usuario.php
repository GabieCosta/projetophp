<?php
require_once 'includes/database.php'; 

class Usuario {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $data_nascimento;
    private $foto_perfil;
    private $biografia;
    private $data_criacao;

    // Construtor
    public function __construct($id = null, $nome= null, $email = null, $senha = null, $data_nascimento = null, $foto_perfil = null, $biografia = null, $data_criacao = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->data_nascimento = $data_nascimento;
        $this->foto_perfil = $foto_perfil;
        $this->biografia = $biografia;
        $this->data_criacao = $data_criacao ?? date("Y-m-d H:i:s");
    }


    public function login($email, $senha)
{
    $database = new Database();
    $pdo = $database->connect();

    // Consulta para verificar se o usuário existe com o email fornecido
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Se as credenciais estiverem corretas, inicia a sessão
        session_start();
        $_SESSION['user'] = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email']
        ];

        header("Location: index.php");
        exit();
        
    } else {
        throw new Exception("Email ou senha incorretos.");
    }
}


    // Método para cadastro de um novo usuário (insere no banco)
    public function cadastro() {
        if (empty($this->email) || empty($this->senha) || empty($this->data_nascimento)) {
            throw new Exception("Email, senha e data de nascimento são obrigatórios.");
        }

        $database = new Database();
        $pdo = $database->connect();
        $sql = "INSERT INTO usuarios (nome, email, senha, data_nascimento, data_criacao) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->nome,
            $this->email,
            password_hash($this->senha, PASSWORD_DEFAULT), // Senha segura
            $this->data_nascimento,
            $this->data_criacao
        ]);
    }

    // Método para atualizar o perfil do usuário (só foto, biografia e senha)
    public function atualiza_perfil() {
        $database = new Database();
        $pdo = $database->connect();
        
        // Atualiza os campos permitidos (foto de perfil, biografia, senha)
        $sql = "UPDATE usuarios SET foto_perfil = ?, biografia = ?, senha = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->foto_perfil,
            $this->biografia,
            password_hash($this->senha, PASSWORD_DEFAULT),
            $this->id
        ]);
    }

    // Método para excluir um usuário pelo ID
    public function excluir_usuario() {
        $database = new Database();
        $pdo = $database->connect();
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
    }

    public static function getUsuarioById($id) {
        $database = new Database();
        $pdo = $database->connect();
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return new Usuario(
                $usuario['id'],
                $usuario['nome'],
                $usuario['email'],
                $usuario['senha'],
                $usuario['data_nascimento'],
                $usuario['foto_perfil'],
                $usuario['biografia'],
                $usuario['data_criacao']
            );
        } else {
            throw new Exception("Usuário não encontrado.");
        }
    }

    // Getters e setters
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getDataNascimento() {
        return $this->data_nascimento;
    }

    public function getFotoPerfil() {
        return $this->foto_perfil;
    }

    public function getBiografia() {
        return $this->biografia;
    }

    public function getDataCriacao() {
        return $this->data_criacao;
    }

    // Setters (para atualizar os dados diretamente, se necessário)
    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setDataNascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }

    public function setFotoPerfil($foto_perfil) {
        $this->foto_perfil = $foto_perfil;
    }

    public function setBiografia($biografia) {
        $this->biografia = $biografia;
    }

    public function setDataCriacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }
}
?>
