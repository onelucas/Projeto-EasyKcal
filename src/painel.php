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

    <?php include 'header.php'; ?>
    
    <div class="painel-container">
        <h2>Bem-vindo(a), <?php echo $_SESSION['nome_usuario']; ?> 👋</h2>
        <p>Você está logado no sistema EasyKcal.</p>

        <div class="painel-links">
            <a href="definirMeta.php" class="btn">Meta de calorias ✔️</a>
            <a href="cadastrarAlimentos.php" class="btn">Alimentos 🥗</a>
            <a href="editarRefeicao.php" class="btn">Minhas Refeições 🍽️</a>
            <a href="consultarHistorico.php" class="btn"> Histórico 📖 </a>
            <a href="logout.php" class="btn logout">Sair 🚪</a>
        </div>
    </div>
</body>
</html>
