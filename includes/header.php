<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MINDLUP - Mind Level Up, uma plataforma para aprimorar seu conhecimento com cursos online.">
    <meta name="keywords" content="cursos, educação, aprendizagem, desenvolvimento, MINDLUP">
    <meta name="author" content="MINDLUP">
    <title>MINDLUP - Mind Level Up</title>
    <link rel="stylesheet" href="includes/styles.css">
</head>
<body>
    <header>
        <nav aria-label="Menu principal">
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="profile.php">Perfil</a></li>
                    <li><a href="logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="registro.php">Cadastro</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

