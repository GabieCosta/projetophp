<?php
require_once 'includes/database.php';
require_once 'classes/cursos.php';

// Recupera o ID do curso a ser visualizado
$curso_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($curso_id > 0) {
    $curso =  Curso::getCursoPorId($curso_id);
    if (!$curso) {
        die('Curso não encontrado.');
    }
} else {
    die('ID do curso inválido.');
}

include 'includes/header.php'; // Inclui o cabeçalho
?>

<main>
    <h1><?php echo htmlspecialchars($curso['titulo']); ?></h1>
    <div>
        <strong>Resumo:</strong>
        <p><?php echo htmlspecialchars($curso['resumo']); ?></p>
    </div>
    <div>
        <strong>Descrição:</strong>
        <p><?php echo htmlspecialchars($curso['descricao']); ?></p>
    </div>
    <div>
        <strong>Área de Estudo:</strong>
        <p><?php echo htmlspecialchars($curso['area_estudo']); ?></p>
    </div>
    <div>
        <strong>Nivelamento:</strong>
        <p><?php echo htmlspecialchars($curso['nivelamento']); ?></p>
    </div>
    <div>
        <strong>Instrutor ID:</strong>
        <p><?php echo htmlspecialchars($curso['instrutor_id']); ?></p>
    </div>
    <div>
        <strong>Data de Criação:</strong>
        <p><?php echo htmlspecialchars($curso['data_criacao']); ?></p>
    </div>
    <div>
        <strong>Imagem:</strong>
        <?php if ($curso['imagem']): ?>
            <p><img src="<?php echo htmlspecialchars($curso['imagem']); ?>" alt="Imagem do curso" style="max-width: 200px;"></p>
        <?php else: ?>
            <p>Sem imagem disponível.</p>
        <?php endif; ?>
    </div>
    <div>
        <a href="lista_comentarios.php?id=<?php echo $curso_id; ?>">Ver Comentários</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>