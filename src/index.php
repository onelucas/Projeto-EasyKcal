<?php
session_start();
include('conexao.php');

// Lógica de login
if (isset($_POST['email']) && isset($_POST['senha'])) {
    if (empty($_POST['email'])) {
        $erro = "Preencha seu e-mail";
    } elseif (empty($_POST['senha'])) {
        $erro = "Preencha sua senha";
    } else {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $_POST['senha'];

        $sql_code = "SELECT * FROM usuario WHERE email = '$email'";
        $sql_query = $mysqli->query($sql_code) or die("Erro: " . $mysqli->error);

        if ($sql_query->num_rows == 1) {
            $usuario = $sql_query->fetch_assoc();

            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['user'] = $usuario['idusuario'];
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];

                header("Location: painel.php");
                exit;
            } else {
                $erro = "Senha incorreta";
            }
        } else {
            $erro = "E-mail não encontrado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - EasyKcal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <img src="/easykcal/assets/img/logo_easykcal.png" alt="Logo EasyKcal" style="max-width: 200px; margin-bottom: 0px;">

    <p class="subtitle">Acesse sua conta</p>

    <?php if (!empty($erro)): ?>
        <div class="error"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="text" name="email" placeholder="exemplo@email.com" required>

        <label>Senha</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required>

        <button type="submit">Entrar</button>
    </form>

    <p class="register-link">Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
</div>
