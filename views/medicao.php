<?php
// views/medicao_view.php
if (!defined('APP_LOADED')) {
    die('Acesso direto não permitido.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/desenvolvimentoprojeto/assets/css/stylemedicao.css"> 
    <title>Análise WiFi - Medições</title>
</head>
<body>

    <h1 id="pageTitle">Medições para: <span><?php echo htmlspecialchars($residencia_nome); ?> / <?php echo htmlspecialchars($comodo_nome); ?></span></h1>
    <div class="container">
        <div class="form-container">
            <h2>Cadastrar Medição</h2>
            <form id="wifiForm" action="/desenvolvimentoprojeto/medicoes/add" method="POST">
                <div>
                    <label for="nivelSinal">Nível de Sinal (dBm):</label>
                    <input type="number" id="nivelSinal" name="nivelSinal" required>
                </div>
                <div>
                    <label for="velocidade">Velocidade (Mbps):</label>
                    <input type="number" id="velocidade" name="velocidade" step="0.01" required>
                </div>
                <div>
                    <label for="interferencia">Interferência:</label>
                    <input type="number" id="interferencia" name="interferencia"  required>
                </div>
                <button type="submit">Salvar</button>
            </form>
            <a href="/desenvolvimentoprojeto/comodos" class="nav-link">Voltar para a Lista de Cômodos</a>
        </div>
        <div class="table-container">
            <h2>Medições Realizadas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nível de Sinal</th>
                        <th>Velocidade</th>
                        <th>Interferência</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="wifiData">
                    <?php foreach ($medicoes as $medicao): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($medicao['Nivel_Sinal']); ?> dBm</td>
                            <td><?php echo htmlspecialchars($medicao['Velocidade']); ?> Mbps</td>
                            <td><?php echo htmlspecialchars($medicao['Interferencia']); ?></td>
                            <td class="action-buttons">
                                <a href="/desenvolvimentoprojeto/medicoes/delete/<?php echo $medicao['Id']; ?>" class="button delete-btn" onclick="return confirm('Deseja deletar?');">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>