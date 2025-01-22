<?php
// Inclua o arquivo que contém a classe de cadastro (ajuste o caminho se necessário)
require_once 'includes/database.php'; 
require_once 'classes/usuario.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Cria um novo objeto de usuário com os parâmetros do formulário
        $usuario = new Usuario(null, $_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['data_nascimento']);
        
        // Usa o setter para definir a data de criação
        $usuario->setDataCriacao(date('Y-m-d H:i:s')); // Data de criação
        
        // Chama o método de cadastro
        $usuario->cadastro();
        echo "<p>Cadastro realizado com sucesso!</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>Erro: " . $e->getMessage() . "</p>";
    }
}

include 'includes/header.php';
?>

<main>
    <div class="login-container">
        <h1>Cadastre-se</h1>
        <form action="registro.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required>
            </div>
            <button type="submit">Cadastrar</button>
        </form>
        <div class="login-link">
            <p>Já tem uma conta? <a href="login.php">Entrar</a></p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
