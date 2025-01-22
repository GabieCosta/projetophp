<?php
session_start();
require_once 'classes/comentarios.php';
require_once 'classes/usuario.php';

$curso_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($curso_id === false) {
    die('ID inválido');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_STRING);
    $usuario_id = $_SESSION['user']['id'];

    if ($texto && $usuario_id && $curso_id) {
        $comentario = new Comentario(null, $curso_id, $usuario_id, $texto);
        $comentario->comentar();
        header("Location: lista_comentarios.php?id=$curso_id");
        exit;
    } else {
        $erro = 'Preencha todos os campos corretamente.';
    }
}

include 'includes/header.php'; // Inclui o cabeçalho
?>

<main>
    <h1>Criar Comentário</h1>
    <?php if (isset($erro)): ?>
        <p><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>
    <form action="cria_comentario.php?id=<?php echo $curso_id; ?>" method="post">
        <label for="texto">Comentário:</label>
        <textarea name="texto" id="texto" required style="width: 100%; height: 150px;"></textarea>
        <button type="submit">Enviar</button>
    </form>
    <a href="lista_comentarios.php?id=<?php echo $curso_id; ?>"><button>Voltar</button></a>
</main>

<?php include 'includes/footer.php'; ?>