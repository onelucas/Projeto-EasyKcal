<?php
// src/header.php
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>
<header class="app-header">
    <?php if ($paginaAtual === 'painel.php'): ?>
        <a href="editarPerfil.php" class="perfil-fixo-btn">Perfil</a>
    <?php else: ?>
        <a href="painel.php" class="menu-voltar-btn">☰ Menu</a>
    <?php endif; ?>

    <div class="logo-fixed-top-right">
        <a href="painel.php"><img src="/easykcal/assets/img/logo_easykcal.png" alt="Logo do APP"></a>
    </div>
</header>
