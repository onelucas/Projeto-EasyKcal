<?php
include('protect.php');
include('conexao.php');

// Função para buscar alimentos disponíveis
function getAlimentos($conn) {
    $sql = "SELECT idalimentos, nome_alimento, qtd_kcal FROM alimentos ORDER BY nome_alimento";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

// Tratamento do POST para adicionar ou editar/excluir
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Adicionar alimento à refeição
    if (isset($_POST['add_refeicao'])) {
        $idalimento = intval($_POST['idalimento']);
        $tipo_refeicao = $_POST['tipo_refeicao'];
        $data_refeicao = $_POST['data_refeicao'];
        $quantidade = floatval($_POST['quantidade']);

        // Buscar qtd_kcal do alimento
        $sql = "SELECT qtd_kcal FROM alimentos WHERE idalimentos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idalimento);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 0) {
            $mensagem = "Alimento não encontrado.";
        } else {
            $row = $res->fetch_assoc();
            $qtd_kcal = $row['qtd_kcal'];
            $kcal_total = ($quantidade * $qtd_kcal) / 100;

            // Inserir na tabela refeicao
            $sql = "INSERT INTO refeicao (usuario_idusuario, data_refeicao, tipo_refeicao, idalimento, quantidade, kcal_total) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issidd", $usuario_id, $data_refeicao, $tipo_refeicao, $idalimento, $quantidade, $kcal_total);
            if ($stmt->execute()) {
                $mensagem = "Alimento adicionado à refeição com sucesso!";
            } else {
                $mensagem = "Erro ao adicionar alimento: " . $conn->error;
            }
        }
    }

    // Editar quantidade de alimento em refeição
    if (isset($_POST['edit_refeicao'])) {
        $idrefeicao = intval($_POST['idrefeicao']);
        $nova_quantidade = floatval($_POST['nova_quantidade']);

        // Buscar idalimento da refeição
        $sql = "SELECT idalimento FROM refeicao WHERE idrefeicao = ? AND usuario_idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idrefeicao, $usuario_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 0) {
            $mensagem = "Refeição não encontrada.";
        } else {
            $row = $res->fetch_assoc();
            $idalimento = $row['idalimento'];

            // Buscar qtd_kcal do alimento
            $sql = "SELECT qtd_kcal FROM alimentos WHERE idalimentos = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idalimento);
            $stmt->execute();
            $res2 = $stmt->get_result();
            $row2 = $res2->fetch_assoc();
            $qtd_kcal = $row2['qtd_kcal'];

            $novo_kcal_total = ($nova_quantidade * $qtd_kcal) / 100;

            // Atualiza refeição
            $sql = "UPDATE refeicao SET quantidade = ?, kcal_total = ? WHERE idrefeicao = ? AND usuario_idusuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("diii", $nova_quantidade, $novo_kcal_total, $idrefeicao, $usuario_id);
            if ($stmt->execute()) {
                $mensagem = "Quantidade atualizada com sucesso!";
            } else {
                $mensagem = "Erro ao atualizar: " . $conn->error;
            }
        }
    }

    // Excluir alimento da refeição
    if (isset($_POST['delete_refeicao'])) {
        $idrefeicao = intval($_POST['idrefeicao']);
        $sql = "DELETE FROM refeicao WHERE idrefeicao = ? AND usuario_idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idrefeicao, $usuario_id);
        if ($stmt->execute()) {
            $mensagem = "Alimento excluído da refeição.";
        } else {
            $mensagem = "Erro ao excluir: " . $conn->error;
        }
    }
}

// Busca todas as refeições do usuário ordenadas por data e tipo
$sql = "SELECT r.idrefeicao, r.data_refeicao, r.tipo_refeicao, a.nome_alimento, r.quantidade, r.kcal_total
        FROM refeicao r
        JOIN alimentos a ON r.idalimento = a.idalimentos
        WHERE r.usuario_idusuario = ?
        ORDER BY r.data_refeicao DESC, 
          FIELD(r.tipo_refeicao, 'cafe_da_manha', 'almoco', 'lanche', 'janta')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$refeicoes = $result->fetch_all(MYSQLI_ASSOC);

// Busca alimentos para o select
$alimentos = getAlimentos($conn);

// Mapeia tipos de refeição para mostrar texto amigável
$tipos_refeicao = [
    'cafe_da_manha' => 'Café da Manhã',
    'almoco' => 'Almoço',
    'lanche' => 'Lanche',
    'janta' => 'Janta'
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Refeições</title>
    <link rel="stylesheet" href="seu_css_geral.css" />
    <style>
        /* Estilos específicos */
        .container-refeicao {
            max-width: 800px;
            margin: 40px auto;
            background-color: #1f1f1f;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            color: white;
        }
        .mensagem {
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        .mensagem.sucesso {
            color: #4caf50;
        }
        .mensagem.erro {
            color: #f44336;
        }
    </style>
</head>
<body>

<div class="container-refeicao painel-container">

    <h2>Gerenciar Refeições</h2>

    <?php if ($mensagem): ?>
        <div class="mensagem <?= strpos($mensagem, 'erro') !== false ? 'erro' : 'sucesso'; ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário para adicionar alimento na refeição -->
    <form method="post">
        <h3>Adicionar alimento à refeição</h3>

        <label for="idalimento">Alimento:</label>
        <select id="idalimento" name="idalimento" required>
            <option value="">-- Selecione --</option>
            <?php foreach ($alimentos as $alimento): ?>
                <option value="<?= $alimento['idalimentos'] ?>">
                    <?= htmlspecialchars($alimento['nome_alimento']) ?> (<?= $alimento['qtd_kcal'] ?> kcal/100g)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tipo_refeicao">Tipo da Refeição:</label>
        <select id="tipo_refeicao" name="tipo_refeicao" required>
            <?php foreach ($tipos_refeicao as $key => $label): ?>
                <option value="<?= $key ?>"><?= $label ?></option>
            <?php endforeach; ?>
        </select>

        <label for="data_refeicao">Data da Refeição:</label>
        <input type="date" id="data_refeicao" name="data_refeicao" value="<?= date('Y-m-d') ?>" required />

        <label for="quantidade">Quantidade (gramas):</label>
        <input type="number" id="quantidade" name="quantidade" min="1" step="0.1" required />

        <button type="submit" name="add_refeicao">Adicionar</button>
    </form>

    <!-- Lista de refeições do usuário -->
    <h3>Refeições registradas</h3>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Alimento</th>
                    <th>Quantidade (g)</th>
                    <th>Calorias</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($refeicoes)): ?>
                    <tr><td colspan="6" style="text-align:center;">Nenhuma refeição cadastrada.</td></tr>
                <?php else: ?>
                    <?php foreach ($refeicoes as $refeicao): ?>
                        <tr>
                            <td><?= htmlspecialchars($refeicao['data_refeicao']) ?></td>
                            <td><?= htmlspecialchars($tipos_refeicao[$refeicao['tipo_refeicao']]) ?></td>
                            <td><?= htmlspecialchars($refeicao['nome_alimento']) ?></td>
                            <td>
                                <form method="post" class="edit-form" style="margin:0;">
                                    <input type="hidden" name="idrefeicao" value="<?= $refeicao['idrefeicao'] ?>" />
                                    <input type="number" name="nova_quantidade" value="<?= $refeicao['quantidade'] ?>" min="1" step="0.1" required />
                                    <button type="submit" name="edit_refeicao">Salvar</button>
                                </form>
                            </td>
                            <td><?= number_format($refeicao['kcal_total'], 2) ?> kcal</td>
                            <td>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este alimento?');">
                                    <input type="hidden" name="idrefeicao" value="<?= $refeicao['idrefeicao'] ?>" />
                                    <button type="submit" name="delete_refeicao" class="btn btn-excluir">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
