<?php
include('protect.php'); 
$usuario_id = $_SESSION['idusuario']; 

$mensagem_sucesso = '';
$mensagem_erro = '';
include('conexao.php');

$usuario_id = (int) $_SESSION['idusuario'];
$meta_calorias = null;

$stmt = $conn->prepare("SELECT meta FROM usuario WHERE idusuario = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dados = $result->fetch_assoc();
    $meta_calorias = $dados['meta'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $usuario_id > 0) {
    $nova_meta = filter_input(INPUT_POST, 'calorias', FILTER_VALIDATE_INT);

    if (!$nova_meta || $nova_meta <= 0) {
        echo "Meta inválida. Digite um número positivo.";
        exit;
    }


    if ($meta_calorias !== null) {
        $stmt = $conn->prepare("UPDATE usuario SET meta = ? WHERE idusuario = ?");
        $stmt->bind_param("ii", $nova_meta, $usuario_id);
        if ($stmt->execute()) {
            $mensagem_sucesso = "Meta de calorias atualizada!";
            $meta_calorias = $nova_meta;
        } else {
            echo "Erro ao atualizar a meta: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Meta - Easykcal</title>
    <link rel="stylesheet" href="style.css">
</head>

    <?php include 'header.php'; ?>

    <div class="meta-container">
        <form method="post">
            <label for="calorias">Defina a sua meta de calorias diárias:</label>
            <input type="number" name="calorias" id="calorias" required min="1" placeholder="Ex: 2200">
            <button type="submit">Salvar</button>
        </form>

        <div class="meta-atual">
            <?php if ($meta_calorias !== null) : ?>
                <div class="valor-meta-destaque">
                    <?= htmlspecialchars($meta_calorias) ?> calorias/dia
                </div>
            <?php else: ?>
                <p>Você ainda não definiu uma meta.</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($mensagem_erro)): ?>
            <div class="mensagem-erro"><?=$mensagem_erro ?></div>
        <?php elseif (!empty($mensagem_sucesso)): ?>
            <div class="mensagem-sucesso"><?=$mensagem_sucesso ?></div>
        <?php endif; ?>
    </div>
    
</html>