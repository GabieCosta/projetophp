<?php
require_once 'includes/database.php'; // Inclui a conexão com o banco de dados
require_once 'classes/cursos.php'; // Inclui a classe Curso

// Se estiver logado, recupera o ID do usuário (caso contrário, mostra o botão de login)
session_start();
if (!isset($_SESSION['user']));

include 'includes/header.php'; // Inclui o cabeçalho ?>
    
    <main>
        <h1>Catálogo de Cursos</h1>

        <form action="index.php" method="GET">
            <div>
                <label for="texto">Pesquisar por texto:</label>
                <input type="text" name="titulo" id="titulo" value="<?php echo isset($_GET['titulo']) ? $_GET['titulo'] : ''; ?>" />
            </div>
            <div>
                <label for="area_estudo">Área de Estudo:</label>
                <select name="area_estudo" id="area_estudo">
                    <option value="">Selecione...</option>
                    <option value="Tecnologia" <?php echo isset($_GET['area_estudo']) && $_GET['area_estudo'] == 'Tecnologia' ? 'selected' : ''; ?>>Tecnologia</option>
                    <option value="Design" <?php echo isset($_GET['area_estudo']) && $_GET['area_estudo'] == 'Design' ? 'selected' : ''; ?>>Design</option>
                    <option value="Marketing" <?php echo isset($_GET['area_estudo']) && $_GET['area_estudo'] == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                </select>
            </div>
            <div>
                <label for="nivelamento">Nivelamento:</label>
                <select name="nivelamento" id="nivelamento">
                    <option value="">Selecione...</option>
                    <option value="Iniciante" <?php echo isset($_GET['nivelamento']) && $_GET['nivelamento'] == 'Iniciante' ? 'selected' : ''; ?>>Iniciante</option>
                    <option value="Intermediário" <?php echo isset($_GET['nivelamento']) && $_GET['nivelamento'] == 'Intermediário' ? 'selected' : ''; ?>>Intermediário</option>
                    <option value="Avançado" <?php echo isset($_GET['nivelamento']) && $_GET['nivelamento'] == 'Avançado' ? 'selected' : ''; ?>>Avançado</option>
                </select>
            </div>

            <?php if (isset($_SESSION['user'])): ?>
            <div>
                <label for="meus_cursos">Meus Cursos:</label>
                <input type="checkbox" name="meus_cursos" id="meus_cursos" <?php echo isset($_GET['meus_cursos']) ? 'checked' : ''; ?> />
            </div>
            <button type="submit">Pesquisar</button>
        </form>
            <?php endif; ?>
        <div>
            </div>

        <div class="curso-lista">
            <?php
            $texto = isset($_GET['titulo']) ? $_GET['titulo'] : '';
            $area_estudo = isset($_GET['area_estudo']) ? $_GET['area_estudo'] : '';
            $nivelamento = isset($_GET['nivelamento']) ? $_GET['nivelamento'] : '';
            $meus_cursos = isset($_GET['meus_cursos']) ? true : false;

            $cursoObj = new Curso();
            $resultados = $cursoObj->pesquisa($texto, $area_estudo, $nivelamento, $meus_cursos ? $_SESSION['user']['id'] : null);

            // Exibe os cursos encontrados
            foreach ($resultados as $curso) {
                echo '<div class="curso-item">';
                echo '<img src="' . $curso['imagem'] . '" alt="Imagem do curso" />';
                if ($curso['instrutor_id'] == $_SESSION['user']['id']) {
                    echo '<h2><a href="editar_curso.php?id=' . $curso['id'] . '">' . $curso['titulo'] . '</a></h2>';
                } else {
                    echo '<h2><a href="curso.php?id=' . $curso['id'] . '">' . $curso['titulo'] . '</a></h2>';
                }
                echo '<p>' . $curso['resumo'] . '</p>';
                echo '</div>';
            }
            ?>
        </div>

        <?php if (isset($_SESSION['user'])): ?>
            <a href="criar_curso.php" class="btn-criar-curso">Crie Seu Curso</a>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php';?>
