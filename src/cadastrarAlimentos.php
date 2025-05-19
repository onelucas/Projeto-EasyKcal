<?php
include('protect.php');
include('conexao.php');

// Mensagens
$mensagem_sucesso = '';
$mensagem_erro = '';

// Excluir alimento
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $conn->query("DELETE FROM alimentos WHERE idalimentos = $id");
    header("Location: cadastrarAlimentos.php");
    exit();
}

// Editar alimento
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_alimento'])) {
    $id = intval($_POST['id_alimento']);
    $nome = trim($_POST['nome_alimento']);
    $kcal = floatval($_POST['qtd_kcal']);

    if ($nome && $kcal > 0) {
        $stmt = $conn->prepare("UPDATE alimentos SET nome_alimento = ?, qtd_kcal = ? WHERE idalimentos = ?");
        $stmt->bind_param("sdi", $nome, $kcal, $id);
        if ($stmt->execute()) {
            header("Location: cadastrarAlimentos.php?edit=" . $id);
            exit();
        } else {
            $mensagem_erro = "Erro ao editar alimento.";
        }
    }
}

// Cadastrar alimento novo
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['id_alimento'])) {
    $nome = trim($_POST['nome_alimento']);
    $kcal = floatval($_POST['qtd_kcal']);

    if ($nome && $kcal > 0) {
        $stmt = $conn->prepare("INSERT INTO alimentos (nome_alimento, qtd_kcal) VALUES (?, ?)");
        $stmt->bind_param("sd", $nome, $kcal);
        if ($stmt->execute()) {
            header("Location: cadastrarAlimentos.php");
            exit();
        } else {
            $mensagem_erro = "Erro ao cadastrar alimento.";
        }
    } else {
        $mensagem_erro = "Preencha todos os campos corretamente.";
    }
}

// Pega alimentos
$resultado = $conn->query("SELECT * FROM alimentos ORDER BY nome_alimento ASC");
$alimento_editando = isset($_GET['edit']) ? intval($_GET['edit']) : null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Alimentos - EasyKcal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="painel-container">
        <h2>Cadastrar Novo Alimento</h2>

        <form method="POST">
            <input type="text" name="nome_alimento" placeholder="Nome do alimento (Quantidade)" required>
            <input type="number" step="0.01" name="qtd_kcal" placeholder="Quantidade de kcal" required>
            <button type="submit" class="btn">Cadastrar</button>
        </form>

        <?php if ($mensagem_sucesso): ?>
            <div class="mensagem-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
        <?php elseif ($mensagem_erro): ?>
            <div class="mensagem-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
        <?php endif; ?>

        <h3>Alimentos Cadastrados</h3>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nome do Alimento (Quantidade)</th>
                        <th>Calorias (kcal)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <tr id="alimento-<?= $row['idalimentos'] ?>">
                            <?php if ($alimento_editando === intval($row['idalimentos'])): ?>
                                <td colspan="3">
                                    <form method="POST" class="edit-form">
                                        <input type="hidden" name="id_alimento" value="<?= $row['idalimentos'] ?>">
                                        <input type="text" name="nome_alimento" value="<?= htmlspecialchars($row['nome_alimento']) ?>" required>
                                        <input type="number" step="0.01" name="qtd_kcal" value="<?= htmlspecialchars($row['qtd_kcal']) ?>" required>
                                        <button type="submit">Salvar</button>
                                        <a href="cadastrarAlimentos.php" class="cancelar-btn">Cancelar</a>
                                    </form>
                                </td>
                            <?php else: ?>
                                <td><?= htmlspecialchars($row['nome_alimento']) ?></td>
                                <td><?= htmlspecialchars($row['qtd_kcal']) ?></td>
                                <td>
                                    <a href="cadastrarAlimentos.php?edit=<?= $row['idalimentos'] ?>" class="btn-editar">Editar</a> |
                                    <a href="cadastrarAlimentos.php?excluir=<?= $row['idalimentos'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este alimento?')">Excluir</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php if ($alimento_editando): ?>
<script>
    window.onload = () => {
        const target = document.getElementById("alimento-<?= $alimento_editando ?>");
        if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    };
</script>
<?php endif; ?>
</body>
</html>
