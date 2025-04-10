<?php
include('protect.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel - EasyKcal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="painel-container">
        <h2>Bem-vindo, <?php echo $_SESSION['nome_usuario']; ?> 👋</h2>
        <p>Você está logado no sistema EasyKcal.</p>

        <div class="painel-links">
            <a href="refeicoes.php" class="btn">Minhas Refeições 🍽️</a>
            <a href="cadastro_alimento.php" class="btn">Adicionar Alimento ➕</a>
            <a href="logout.php" class="btn logout">Sair 🚪</a>
        </div>
    </div>
</body>
</html>
