<?php
require_once 'includes/database.php';
require_once 'classes/usuario.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user']['id'];
$usuario = Usuario::getUsuarioById($userId);


if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
    $fileName = $_FILES['foto_perfil']['name'];
    $fileSize = $_FILES['foto_perfil']['size'];
    $fileType = $_FILES['foto_perfil']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
    if (in_array($fileExtension, $allowedfileExtensions)) {
        $uploadFileDir = './imagens/';
        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $usuario->setFotoPerfil($dest_path);
        } else {
            throw new Exception('There was an error moving the uploaded file.');
        }
    } else {
        throw new Exception('Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions));
    }
} else {
    $usuario->setFotoPerfil($usuario->getFotoPerfil());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $usuario->setSenha(password_hash($_POST['senha'], PASSWORD_DEFAULT));
        $usuario->setBiografia($_POST['biografia']);

        $usuario->atualiza_perfil();
        echo "<p>Perfil atualizado com sucesso!</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>Erro: " . $e->getMessage() . "</p>";
    }
}

include 'includes/header.php';
?>

<main>
    <div class="profile-container">
        <h1>Editar Perfil</h1>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="nome">Nome: <?php echo htmlspecialchars($usuario->getNome()); ?> </label>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="foto_perfil">Foto de Perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil">
            </div>
            <div class="form-group">
                <label for="biografia">Biografia:</label>
                <textarea id="biografia"
                    name="biografia"><?php echo htmlspecialchars($usuario->getBiografia()); ?></textarea>
            </div>
            <button type="submit">Atualizar Perfil</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>