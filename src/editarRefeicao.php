<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['idusuario'])) {
    die("Você precisa estar logado para acessar esta página.<p><a href=\"index.php\">Entrar</a></p>");
}

$idusuario = $_SESSION['idusuario'];
$dataHoje = date('Y-m-d');

// Pega a meta do usuário
$sql_meta = $conn->prepare("SELECT meta FROM usuario WHERE idusuario = ?");
$sql_meta->bind_param("i", $idusuario);
$sql_meta->execute();
$result_meta = $sql_meta->get_result();
$meta_usuario = 0;
if ($row = $result_meta->fetch_assoc()) {
    $meta_usuario = floatval($row['meta']);
}

// Função para calcular calorias totais do dia
function totalCaloriasDia($conn, $idusuario, $data) {
    $sql = $conn->prepare("SELECT SUM(kcal_total) AS total FROM refeicao WHERE usuario_idusuario = ? AND data_refeicao = ?");
    $sql->bind_param("is", $idusuario, $data);
    $sql->execute();
    $res = $sql->get_result();
    if ($row = $res->fetch_assoc()) {
        return floatval($row['total']) ?: 0;
    }
    return 0;
}

// Inserção de alimento na refeição
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $tipo_refeicao = $_POST['tipo_refeicao'] ?? '';
    $idalimento = $_POST['idalimento'] ?? '';
    $quantidade = $_POST['quantidade'] ?? '';

    if (empty($tipo_refeicao) || empty($idalimento) || empty($quantidade) || !is_numeric($quantidade) || $quantidade <= 0) {
        $mensagem = '<p class="mensagem erro">Preencha todos os campos corretamente.</p>';
    } else {
        // Pega calorias do alimento
        $sql_alimento = $conn->prepare("SELECT qtd_kcal FROM alimentos WHERE idalimentos = ?");
        $sql_alimento->bind_param("i", $idalimento);
        $sql_alimento->execute();
        $res_alimento = $sql_alimento->get_result();

        if ($alimento = $res_alimento->fetch_assoc()) {
            $kcal_por_unidade = floatval($alimento['qtd_kcal']);
            $kcal_total = $kcal_por_unidade * floatval($quantidade);

            // Insere na tabela refeicao
            $sql_insere = $conn->prepare("INSERT INTO refeicao (usuario_idusuario, data_refeicao, tipo_refeicao, idalimento, quantidade, kcal_total) VALUES (?, ?, ?, ?, ?, ?)");
            $sql_insere->bind_param("issidd", $idusuario, $dataHoje, $tipo_refeicao, $idalimento, $quantidade, $kcal_total);

            if ($sql_insere->execute()) {
                $mensagem = '<p class="mensagem sucesso">Alimento adicionado com sucesso!</p>';
            } else {
                $mensagem = '<p class="mensagem erro">Erro ao adicionar alimento.</p>';
            }
        } else {
            $mensagem = '<p class="mensagem erro">Alimento não encontrado.</p>';
        }
    }
}

// Edição (update) de um alimento na refeição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $idrefeicao = $_POST['idrefeicao'] ?? '';
    $nova_quantidade = $_POST['quantidade'] ?? '';

    if (empty($idrefeicao) || empty($nova_quantidade) || !is_numeric($nova_quantidade) || $nova_quantidade <= 0) {
        $mensagem = '<p class="mensagem erro">Quantidade inválida para edição.</p>';
    } else {
        // Pega o alimento para recalcular kcal
        $sql_get = $conn->prepare("SELECT idalimento FROM refeicao WHERE idrefeicao = ? AND usuario_idusuario = ?");
        $sql_get->bind_param("ii", $idrefeicao, $idusuario);
        $sql_get->execute();
        $res_get = $sql_get->get_result();

        if ($row = $res_get->fetch_assoc()) {
            $idalimento = $row['idalimento'];
            $sql_kcal = $conn->prepare("SELECT qtd_kcal FROM alimentos WHERE idalimentos = ?");
            $sql_kcal->bind_param("i", $idalimento);
            $sql_kcal->execute();
            $res_kcal = $sql_kcal->get_result();
            if ($al = $res_kcal->fetch_assoc()) {
                $kcal_total = floatval($al['qtd_kcal']) * floatval($nova_quantidade);

                // Atualiza refeição
                $sql_upd = $conn->prepare("UPDATE refeicao SET quantidade = ?, kcal_total = ? WHERE idrefeicao = ? AND usuario_idusuario = ?");
                $sql_upd->bind_param("diii", $nova_quantidade, $kcal_total, $idrefeicao, $idusuario);
                if ($sql_upd->execute()) {
                    $mensagem = '<p class="mensagem sucesso">Quantidade atualizada com sucesso!</p>';
                } else {
                    $mensagem = '<p class="mensagem erro">Erro ao atualizar quantidade.</p>';
                }
            } else {
                $mensagem = '<p class="mensagem erro">Alimento não encontrado para cálculo.</p>';
            }
        } else {
            $mensagem = '<p class="mensagem erro">Refeição não encontrada para edição.</p>';
        }
    }
}

// Exclusão de um alimento na refeição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
    $idrefeicao = $_POST['idrefeicao'] ?? '';

    if (!empty($idrefeicao)) {
        $sql_del = $conn->prepare("DELETE FROM refeicao WHERE idrefeicao = ? AND usuario_idusuario = ?");
        $sql_del->bind_param("ii", $idrefeicao, $idusuario);
        if ($sql_del->execute()) {
            $mensagem = '<p class="mensagem sucesso">Alimento excluído com sucesso!</p>';
        } else {
            $mensagem = '<p class="mensagem erro">Erro ao excluir alimento.</p>';
        }
    }
}

// Busca alimentos para o select do formulário
$sql_alimentos = $conn->query("SELECT idalimentos, nome_alimento FROM alimentos ORDER BY nome_alimento");

// Busca as refeições do dia, agrupadas por tipo
$sql_refeicoes = $conn->prepare("SELECT r.*, a.nome_alimento FROM refeicao r INNER JOIN alimentos a ON r.idalimento = a.idalimentos WHERE r.usuario_idusuario = ? AND r.data_refeicao = ? ORDER BY r.tipo_refeicao, a.nome_alimento");
$sql_refeicoes->bind_param("is", $idusuario, $dataHoje);
$sql_refeicoes->execute();
$res_refeicoes = $sql_refeicoes->get_result();

$refeicoes_por_tipo = [
    'cafe_da_manha' => [],
    'almoco' => [],
    'lanche' => [],
    'janta' => []
];

while ($row = $res_refeicoes->fetch_assoc()) {
    $refeicoes_por_tipo[$row['tipo_refeicao']][] = $row;
}

$total_consumido = totalCaloriasDia($conn, $idusuario, $dataHoje);
$meta_restante = $meta_usuario - $total_consumido;
if ($meta_restante < 0) $meta_restante = 0;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Editar Refeição - EasyKcal</title>
    <link rel="stylesheet" href="style-refeicao.css" />
</head>
<body>
<div class="container-refeicao">

    <h2>Adicionar alimento às refeições (<?php echo $dataHoje; ?>)</h2>

    <?php echo $mensagem ?? ''; ?>

    <!-- Formulário para adicionar alimento -->
    <form method="POST">
        <input type="hidden" name="acao" value="adicionar" />
        
        <label for="tipo_refeicao">Refeição:</label>
        <select name="tipo_refeicao" id="tipo_refeicao" required>
            <option value="">Selecione a refeição</option>
            <option value="cafe_da_manha">Café da Manhã</option>
            <option value="almoco">Almoço</option>
            <option value="lanche">Lanche</option>
            <option value="janta">Janta</option>
        </select>

        <label for="idalimento">Alimento:</label>
        <select name="idalimento" id="idalimento" required>
            <option value="">Selecione o alimento</option>
            <?php while($alimento = $sql_alimentos->fetch_assoc()): ?>
                <option value="<?php echo $alimento['idalimentos']; ?>">
                    <?php echo htmlspecialchars($alimento['nome_alimento']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="quantidade">Quantidade (unidades/porções):</label>
        <input type="number" name="quantidade" id="quantidade" step="0.01" min="0.01" required />

        <button type="submit">Adicionar</button>
    </form>

    <hr/>

    <h3>Total consumido hoje: <?php echo number_format($total_consumido, 2); ?> kcal</h3>
    <h3>Meta restante: <?php echo number_format($meta_restante, 2); ?> kcal</h3>

    <!-- Listagem por refeições -->
    <?php foreach ($refeicoes_por_tipo as $tipo => $itens): ?>
        <section>
            <h3>
                <?php 
                switch($tipo) {
                    case 'cafe_da_manha': echo "Café da Manhã"; break;
                    case 'almoco': echo "Almoço"; break;
                    case 'lanche': echo "Lanche"; break;
                    case 'janta': echo "Janta"; break;
                }
                ?>
            </h3>
            <?php if (count($itens) === 0): ?>
                <p>Nenhum alimento adicionado.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Alimento</th>
                            <th>Quantidade</th>
                            <th>Calorias</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($itens as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nome_alimento']); ?></td>
                                <td><?php echo number_format($item['quantidade'], 2); ?></td>
                                <td><?php echo number_format($item['kcal_total'], 2); ?></td>
                                <td>
                                    <!-- Form para editar -->
                                    <form class="edit-form" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="acao" value="editar" />
                                        <input type="hidden" name="idrefeicao" value="<?php echo $item['idrefeicao']; ?>" />
                                        <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" step="0.01" min="0.01" required />
                                        <button type="submit" title="Atualizar quantidade">Salvar</button>
                                    </form>

                                    <!-- Form para excluir -->
                                    <form method="POST" style="display:inline-block;" onsubmit="return confirm('Deseja realmente excluir este alimento?');">
                                        <input type="hidden" name="acao" value="excluir" />
                                        <input type="hidden" name="idrefeicao" value="<?php echo $item['idrefeicao']; ?>" />
                                        <button class="btn-excluir" type="submit" title="Excluir alimento">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>

</div>

</body>
</html>
