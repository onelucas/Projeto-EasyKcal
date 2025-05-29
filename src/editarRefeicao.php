<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['idusuario'])) {
    die("Você precisa estar logado.<p><a href=\"index.php\">Entrar</a></p>");
}

$idUsuario = $_SESSION['idusuario'];
$dataHoje = date('Y-m-d');
$mensagem = '';

// EDIÇÃO DE ALIMENTO NA REFEIÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_idrefeicao'], $_POST['nova_quantidade'])) {
    $idrefeicao = intval($_POST['editar_idrefeicao']);
    $nova_quantidade = floatval($_POST['nova_quantidade']);

    $stmt = $conn->prepare("SELECT idalimento FROM refeicao WHERE idrefeicao = ? AND usuario_idusuario = ?");
    $stmt->bind_param("ii", $idrefeicao, $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $idalimento = $row['idalimento'];

        $stmtKcal = $conn->prepare("SELECT qtd_kcal FROM alimentos WHERE idalimentos = ?");
        $stmtKcal->bind_param("i", $idalimento);
        $stmtKcal->execute();
        $resultKcal = $stmtKcal->get_result();

        if ($kcal = $resultKcal->fetch_assoc()) {
            $novo_total = $nova_quantidade * $kcal['qtd_kcal'];

            $stmtUpdate = $conn->prepare("UPDATE refeicao SET quantidade = ?, kcal_total = ? WHERE idrefeicao = ? AND usuario_idusuario = ?");
            $stmtUpdate->bind_param("ddii", $nova_quantidade, $novo_total, $idrefeicao, $idUsuario);
            $stmtUpdate->execute();

            $mensagem = "Alimento atualizado com sucesso!";
        }
    }
}

// INSERÇÃO DE ALIMENTO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idalimento'], $_POST['quantidade'], $_POST['tipo_refeicao']) && !isset($_POST['editar_idrefeicao'])) {
    $idalimento = $_POST['idalimento'];
    $quantidade = floatval($_POST['quantidade']);
    $tipo_refeicao = $_POST['tipo_refeicao'];

    $stmtKcal = $conn->prepare("SELECT qtd_kcal FROM alimentos WHERE idalimentos = ?");
    $stmtKcal->bind_param("i", $idalimento);
    $stmtKcal->execute();
    $resultKcal = $stmtKcal->get_result();

    if ($row = $resultKcal->fetch_assoc()) {
        $kcal_total = $quantidade * $row['qtd_kcal'];

        $stmtInsert = $conn->prepare("INSERT INTO refeicao (usuario_idusuario, data_refeicao, tipo_refeicao, idalimento, quantidade, kcal_total) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("issiid", $idUsuario, $dataHoje, $tipo_refeicao, $idalimento, $quantidade, $kcal_total);

        if ($stmtInsert->execute()) {
            $mensagem = "Alimento adicionado com sucesso!";
        } else {
            $mensagem = "Erro ao adicionar alimento.";
        }
    }

    // Atualiza ou insere o total no calorias_diarias
    $stmtSoma = $conn->prepare("SELECT SUM(kcal_total) AS total FROM refeicao WHERE usuario_idusuario = ? AND data_refeicao = ?");
    $stmtSoma->bind_param("is", $idUsuario, $dataHoje);
    $stmtSoma->execute();
    $resultSoma = $stmtSoma->get_result();
    $totalDia = $resultSoma->fetch_assoc()['total'] ?? 0;

    $stmtCheck = $conn->prepare("SELECT id FROM calorias_diarias WHERE usuario_idusuario = ? AND data_registro = ?");
    $stmtCheck->bind_param("is", $idUsuario, $dataHoje);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $stmtUpdate = $conn->prepare("UPDATE calorias_diarias SET total_kcal = ? WHERE usuario_idusuario = ? AND data_registro = ?");
        $stmtUpdate->bind_param("dis", $totalDia, $idUsuario, $dataHoje);
        $stmtUpdate->execute();
    } else {
        $stmtInsertKcal = $conn->prepare("INSERT INTO calorias_diarias (usuario_idusuario, data_registro, total_kcal) VALUES (?, ?, ?)");
        $stmtInsertKcal->bind_param("isd", $idUsuario, $dataHoje, $totalDia);
        $stmtInsertKcal->execute();
    }
}

// EXCLUSÃO
if (isset($_GET['excluir'])) {
    $idrefeicao = intval($_GET['excluir']);
    $stmtDel = $conn->prepare("DELETE FROM refeicao WHERE idrefeicao = ? AND usuario_idusuario = ?");
    $stmtDel->bind_param("ii", $idrefeicao, $idUsuario);
    $stmtDel->execute();
    header("Location: editarRefeicao.php");
    exit;
}

// DADOS PARA EXIBIÇÃO
$stmtMeta = $conn->prepare("SELECT meta FROM usuario WHERE idusuario = ?");
$stmtMeta->bind_param("i", $idUsuario);
$stmtMeta->execute();
$resultMeta = $stmtMeta->get_result();
$metaUsuario = ($row = $resultMeta->fetch_assoc()) ? $row['meta'] : 0;

$stmtAlimentos = $conn->prepare("SELECT idalimentos, nome_alimento, qtd_kcal FROM alimentos ORDER BY nome_alimento");
$stmtAlimentos->execute();
$alimentos = $stmtAlimentos->get_result()->fetch_all(MYSQLI_ASSOC);

$tipos_refeicao = ['cafe_da_manha', 'almoco', 'lanche', 'janta'];
$refeicoes = [];

foreach ($tipos_refeicao as $tipo) {
    $stmtRefeicao = $conn->prepare("
        SELECT r.idrefeicao, a.nome_alimento, r.quantidade, a.qtd_kcal, r.kcal_total
        FROM refeicao r
        JOIN alimentos a ON r.idalimento = a.idalimentos
        WHERE r.usuario_idusuario = ? AND r.data_refeicao = ? AND r.tipo_refeicao = ?
        ORDER BY r.idrefeicao DESC
    ");
    $stmtRefeicao->bind_param("iss", $idUsuario, $dataHoje, $tipo);
    $stmtRefeicao->execute();
    $refeicoes[$tipo] = $stmtRefeicao->get_result()->fetch_all(MYSQLI_ASSOC);
}

$calorias_consumidas = 0;
foreach ($refeicoes as $lista) {
    foreach ($lista as $refeicao) {
        $calorias_consumidas += $refeicao['kcal_total'];
    }
}
$meta_restante = $metaUsuario - $calorias_consumidas;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Refeições - EasyKcal</title>
    <link rel="stylesheet" href="style-refeicao.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container-refeicao">
    <h2>Minhas Refeições</h2>

    <?php if (!empty($mensagem)): ?>
        <div class="mensagem sucesso"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <div class="status-kcal">
        <p>Total consumido hoje: <strong><?= number_format($calorias_consumidas, 2, ',', '.') ?> kcal</strong></p>
        <p>Meta diária: <strong><?= number_format($metaUsuario, 2, ',', '.') ?> kcal</strong></p>
        <p>Restante: <strong><?= number_format($meta_restante, 2, ',', '.') ?> kcal</strong></p>
    </div>

    <form method="POST">
        <h3>Adicionar Alimento</h3>
        <label for="tipo_refeicao">Refeição</label>
        <select name="tipo_refeicao" required>
            <option value="">Selecione</option>
            <option value="cafe_da_manha">Café da Manhã</option>
            <option value="almoco">Almoço</option>
            <option value="lanche">Lanche</option>
            <option value="janta">Janta</option>
        </select>

        <label for="filtro-alimento">Pesquisar Alimento</label>
        <input type="text" id="filtro-alimento" placeholder="Digite para filtrar...">

        <select name="idalimento" id="select-alimento" required>
            <option value="">Selecione</option>
            <?php foreach ($alimentos as $alimento): ?>
                <option value="<?= $alimento['idalimentos'] ?>">
                    <?= htmlspecialchars($alimento['nome_alimento']) ?> (<?= $alimento['qtd_kcal'] ?> kcal/porção)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="quantidade">Quantidade de porções</label>
        <input type="number" step="1" name="quantidade" required>

        <button type="submit">Adicionar</button>
    </form>

    <hr style="margin: 30px 0; border-color: #444;">

    <?php
    $nomes_formatados = [
        'cafe_da_manha' => 'Café da Manhã',
        'almoco' => 'Almoço',
        'lanche' => 'Lanche',
        'janta' => 'Janta'
    ];
    ?>

    <?php foreach ($refeicoes as $tipo => $dados): ?>
        <h3><?= $nomes_formatados[$tipo] ?? ucfirst($tipo) ?></h3>
        <?php if (count($dados) > 0): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Alimento</th>
                            <th>Qtd (porções)</th>
                            <th>Calorias por porção</th>
                            <th>Total kcal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_kcal_refeicao = 0;
                        foreach ($dados as $linha):
                            $total_kcal_refeicao += $linha['kcal_total'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($linha['nome_alimento']) ?></td>
                                <td><?= number_format($linha['quantidade'], 0, ',', '.') ?></td>
                                <td><?= number_format($linha['qtd_kcal'], 2, ',', '.') ?> kcal</td>
                                <td><?= number_format($linha['kcal_total'], 2, ',', '.') ?></td>
                                <td>
                                    <form class="edit-form" method="post" style="display:inline;" action="editarRefeicao.php">
                                        <input type="hidden" name="editar_idrefeicao" value="<?= $linha['idrefeicao'] ?>">
                                        <input type="number" name="nova_quantidade" step="1" value="<?= (int)$linha['quantidade'] ?>" required>
                                        <button type="submit">Salvar</button>
                                    </form>
                                    <a class="btn-excluir" href="?excluir=<?= $linha['idrefeicao'] ?>" onclick="return confirm('Deseja excluir este item?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p><strong>Total de calorias nesta refeição:</strong> <?= number_format($total_kcal_refeicao, 2, ',', '.') ?> kcal</p>
        <?php else: ?>
            <p>Nenhum alimento registrado nesta refeição.</p>
        <?php endif; ?>
        <hr>
    <?php endforeach; ?>
</div>

<script>
document.getElementById("filtro-alimento").addEventListener("input", function () {
    let filtro = this.value.toLowerCase();
    let opcoes = document.querySelectorAll("#select-alimento option");
    opcoes.forEach(opt => {
        opt.style.display = (opt.text.toLowerCase().includes(filtro) || opt.value === "") ? "" : "none";
    });
});
</script>

</body>
</html>
