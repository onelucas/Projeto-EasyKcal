<?php
include('protect.php'); 
$usuario_id = $_SESSION['idusuario'];

include('conexao.php');
$idUsuario = $_SESSION['idusuario'];
$mensagem = '';
$dataSelecionada = '';
$meta = null;
$totalConsumido = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $dataSelecionada = $_POST['data'];

    // Buscar a meta do usuário
    $stmtMeta = $conn->prepare("SELECT meta FROM usuario WHERE idusuario = ?");
    $stmtMeta->bind_param("i", $idUsuario);
    $stmtMeta->execute();
    $resultMeta = $stmtMeta->get_result();
    if ($rowMeta = $resultMeta->fetch_assoc()) {
        $meta = $rowMeta['meta'];
    }

    // Buscar calorias consumidas na data
    $stmtConsumo = $conn->prepare("SELECT total_kcal FROM calorias_diarias WHERE usuario_idusuario = ? AND data_registro = ?");
    $stmtConsumo->bind_param("is", $idUsuario, $dataSelecionada);
    $stmtConsumo->execute();
    $resultConsumo = $stmtConsumo->get_result();
    if ($rowConsumo = $resultConsumo->fetch_assoc()) {
        $totalConsumido = $rowConsumo['total_kcal'];
    } else {
        $totalConsumido = 0;
        $mensagem = "Nenhum registro encontrado para esta data.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Desempenho</title>
    <link rel="stylesheet" href="style-refeicao.css">
</head>
<body>
    
<?php include 'header.php'; ?>

<div class="container-refeicao">
    <h2>Histórico de Desempenho</h2>

    <form method="POST">
        <label for="data">Selecione a data:</label>
        <input type="date" name="data" required value="<?= htmlspecialchars($dataSelecionada) ?>">
        <button type="submit">Buscar</button>
    </form>

    <?php if ($dataSelecionada): ?>
        <div class="resumo-calorias">
            <h3>Resultados para <?= date('d/m/Y', strtotime($dataSelecionada)) ?></h3>
            <p><strong>Meta diária:</strong> <?= $meta !== null ? number_format($meta, 2, ',', '.') . ' kcal' : 'Não definida' ?></p>
            <p><strong>Calorias consumidas:</strong> <?= number_format($totalConsumido, 2, ',', '.') ?> kcal</p>
            <?php if (!empty($mensagem)): ?>
                <p style="color: #f44336;"><?= $mensagem ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
