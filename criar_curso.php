<?php
require_once 'includes/database.php'; // Inclui a conexão com o banco de dados
require_once 'classes/cursos.php'; // Inclui a classe Curso

// Se estiver logado, recupera o ID do usuário (caso contrário, redireciona para a página de login)
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $resumo = $_POST['resumo'];
    $descricao = $_POST['descricao'];
    $area_estudo = $_POST['area_estudo'];
    $nivelamento = $_POST['nivelamento'];
    $instrutor_id = $_SESSION['user']['id'];

    // Check required fields
    if (empty($titulo) || empty($resumo) || empty($descricao) || empty($area_estudo) || empty($nivelamento)) {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    }

    // Handle file upload
    if (!$erro && isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'imagens/';
        $uploadFile = $uploadDir . basename($_FILES['imagem']['name']);
        
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
            $imagem = $uploadFile;
        } else {
            $erro = 'Erro ao fazer upload da imagem.';
        }
    } else {
        $erro = 'Por favor, selecione uma imagem para fazer upload.';
    }

    if (!$erro) {
        try {
            $curso = new Curso(null, $titulo, $resumo, $descricao, $area_estudo, $nivelamento, $imagem, $instrutor_id);
            $curso->cria_curso();
            $sucesso = 'Curso criado com sucesso!';
        } catch (Exception $e) {
            $erro = $e->getMessage();
        }
    }
}

include 'includes/header.php'; // Inclui o cabeçalho
?>

<main>
    <h1>Criar Novo Curso</h1>
    <?php if ($erro): ?>
        <div style="color: red; background-color: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px;" role="alert"><?php echo $erro; ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div style="color: green; background-color: #d4edda; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px;" role="alert"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    <br>

    <form action="criar_curso.php" method="POST" enctype="multipart/form-data">
        <div style="padding-right: 10px;">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" />
        </div>
        
        <div>
            <label for="nivelamento">Nivelamento:</label>
            <select name="nivelamento" id="nivelamento">
                <option value="" disabled>Selecione...</option>
                <option value="Iniciante">Iniciante</option>
                <option value="Intermediário">Intermediário</option>
                <option value="Avançado">Avançado</option>
            </select>
        </div>

        <div>
            <label for="area_estudo">Área de Estudo:</label>
            <select name="area_estudo" id="area_estudo" >
                <option value="" disabled>Selecione...</option>
                <option value="Tecnologia">Tecnologia</option>
                <option value="Design">Design</option>
                <option value="Marketing">Marketing</option>
            </select>
        </div>

        <p></p>

        <div>
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao"></textarea>
        </div>

        <p></p>

        <div>
            <label for="resumo">Resumo:</label>
            <textarea name="resumo" id="resumo"></textarea>
        </div>
        
        <br>

        <div>
            <label for="imagem">Imagem:</label>
            <input type="file" name="imagem" id="imagem"/>
        </div>
        <button type="submit">Criar Curso</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>