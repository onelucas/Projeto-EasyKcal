<?php
include('conexao.php');

// Cadastro Process
if (isset($_POST['email_cadastro']) || isset($_POST['senha_cadastro']) || isset($_POST['nome_cadastro'])) {
    if (strlen($_POST['email_cadastro']) == 0 || strlen($_POST['senha_cadastro']) == 0 || strlen($_POST['nome_cadastro']) == 0) {
        echo "Preencha todos os campos para cadastro!";
    } else {
        $nome = $mysqli->real_escape_string($_POST['nome_cadastro']);
        $email_cadastro = $mysqli->real_escape_string($_POST['email_cadastro']);
        $senha_cadastro = $_POST['senha_cadastro'];

        // Criptografar a senha
        $senha_criptografada = password_hash($senha_cadastro, PASSWORD_DEFAULT);

        // Verificar se o email já está cadastrado
        $sql_check = "SELECT * FROM usuarios WHERE email = '$email_cadastro'";
        $sql_check_query = $mysqli->query($sql_check);

        if ($sql_check_query->num_rows > 0) {
            echo "E-mail já cadastrado. Tente outro e-mail.";
        } else {
            // Inserir novo usuário com senha criptografada
            $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email_cadastro', '$senha_criptografada')";
            if ($mysqli->query($sql_insert)) {
                echo "Cadastro realizado com sucesso!";
                echo "<br><a href='index.php'>Clique aqui para fazer login</a>";
            } else {
                echo "Erro ao cadastrar: " . $mysqli->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>

<body>
    <h1>Cadastre-se</h1>

    <!-- Cadastro Form -->
    <form action="" method="POST">
        <p>
            <label>Nome</label>
            <input type="text" name="nome_cadastro">
        </p>
        <p>
            <label>E-mail</label>
            <input type="text" name="email_cadastro">
        </p>
        <p>
            <label>Senha</label>
            <input type="password" name="senha_cadastro">
        </p>
        <p>
            <button type="submit">Cadastrar</button>
        </p>
    </form>

    <hr>

    
    <p>Já tem uma conta? <a href="index.php">Faça login</a></p>
</body>

</html>