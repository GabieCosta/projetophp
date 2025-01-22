<?php

session_start();
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

require_once 'includes/database.php'; 
require_once 'classes/usuario.php'; 


$error = '';
$email = "";
$senha = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        $usuario = new Usuario();
        $usuario->login($email, $senha);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'includes/header.php';

?>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" onsubmit="validarLogin(event)">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Digite seu email">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="Digite sua senha">
            </div>
            <button type="submit">Entrar</button>
        </form>
        <div class="register-link">
            <p>Não tem uma conta? <a href="registro.php">Cadastre-se</a></p>
        </div>
    </div>
    <?php 
    include 'includes/footer.php';?>
