<?php
include('conexao.php');

if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])) {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $verifica = $conn->query("SELECT * FROM usuario WHERE email = '$email'");
    if ($verifica->num_rows > 0) {
        $erro = "E-mail já cadastrado!";
    } else {
        $sql = "INSERT INTO usuario (nome_usuario, email, senha, meta) VALUES ('$nome', '$email', '$senha', 0)";
        if ($conn->query($sql)) {
            header("Location: index.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - EasyKcal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="logo-fixed-top-right">
        <img src="/easykcal/assets/img/logo_easykcal.png" alt="Logo EasyKcal" style="max-width: 200px;">
    </div>
    <div class="login-container">
        <h2>Cadastro 📝</h2>
        <p class="subtitle">Crie sua conta gratuita</p>

        <?php if (!empty($erro)): ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome</label>
            <input type="text" name="nome" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <button type="submit">Cadastrar</button>
        </form>

        <p class="register-link">Já tem uma conta? <a href="index.php">Faça login</a></p>
    </div>
</body>
</html>
