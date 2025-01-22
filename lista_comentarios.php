<?php
session_start();
require_once 'classes/comentarios.php';
require_once 'classes/usuario.php';

$curso_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($curso_id === false) {
    die('ID inválido');
}
$lista_comentarios = Comentario::pegar_comentarios_por_curso($curso_id);

include 'includes/header.php'; // Inclui o cabeçalho
?>

<?php

if(isset($_GET['comentario_id'])){
    $comentario = new Comentario($_GET['comentario_id'], $curso_id, $_SESSION['user']['id'], '');
    $comentario->excluir_comentario();
    header("Location: lista_comentarios.php?id=" . $curso_id);
    exit();
}

?>

<main>
    <h1>Comentários</h1>
    <?php if (empty($lista_comentarios)): ?>
        <p>Sem comentários</p>
    <?php else: ?>
        <ul>
            <?php foreach ($lista_comentarios as $comentario): ?>
                <?php $usuario = Usuario::getUsuarioById($comentario['usuario_id']); ?>
                <li>
                    <p><strong>Usuário:</strong> <?php echo htmlspecialchars($usuario->getNome()); ?></p>
                    <p><strong>Comentário:</strong> <?php echo $comentario['comentario'] ?></p>
                    <p><strong>Data:</strong> <?php echo $comentario['created_at'] ?></p>
                    <?php if ($_SESSION['user']['id'] == $comentario['usuario_id']): ?>
                        <a href="lista_comentarios.php?<?php echo "id=". $curso_id . "&comentario_id=" . $comentario['id']?>">Deletar</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <a href="cria_comentario.php?id=<?php echo $curso_id; ?>"><button>Criar Comentário</button></a>
</main>

<?php include 'includes/footer.php'; ?>