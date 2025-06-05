<?php
include('protect.php');
include('conexao.php');

$id = $_SESSION['idusuario'];
$erro = '';
$sucesso = '';


$sql = "SELECT nome_usuario, email FROM usuario WHERE idusuario = $id";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = trim($_POST['nome']);
    $novo_email = trim($_POST['email']);
    $nova_senha = $_POST['senha'];

    if (empty($novo_nome) || empty($novo_email)) {
        $erro = "Nome e e-mail não podem estar vazios.";
    } else {
        if (!empty($nova_senha)) {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $update = "UPDATE usuario SET nome_usuario = ?, email = ?, senha = ? WHERE idusuario = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("sssi", $novo_nome, $novo_email, $senha_hash, $id);
        } else {
            $update = "UPDATE usuario SET nome_usuario = ?, email = ? WHERE idusuario = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("ssi", $novo_nome, $novo_email, $id);
        }

        if ($stmt->execute()) {
            $sucesso = "Dados atualizados com sucesso!";
            $_SESSION['nome_usuario'] = $novo_nome;

            
            $usuario['nome_usuario'] = $novo_nome;
            $usuario['email'] = $novo_email;
        } else {
            $erro = "Erro ao atualizar os dados.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - EasyKcal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="login-container">
    <h2>Editar Perfil</h2>

    <?php if (!empty($erro)) echo "<div class='error'>$erro</div>"; ?>
    <?php if (!empty($sucesso)) echo "<div class='success'>$sucesso</div>"; ?>

    <form method="POST">
        <label>Nome</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome_usuario']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

        <label>Nova Senha</label>
        <input type="password" name="senha" placeholder="Deixe em branco para não alterar">

        <button type="submit">Salvar</button>
    </form>
</div>
</body>
</html>
