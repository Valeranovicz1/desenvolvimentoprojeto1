<?php

if (!defined('APP_LOADED')) {
    die('Acesso direto não permitido.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/desenvolvimentoprojeto/assets/css/styleresidencia.css"> 
    <title>Análise WiFi - Residências</title>
</head>
<body>

    <h1>Gerenciador de Residências</h1>
    <div class="container">
        <div class="form-container">
            <h2>Cadastrar Nova Residência</h2>
            
            <form id="residenciaForm" action="/desenvolvimentoprojeto/residencias/add" method="POST">
                <div>
                    <label for="nomeResidencia">Nome da Residência:</label>
                    <input type="text" id="nomeResidencia" name="nomeResidencia" required>
                </div>
                <div>
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                <button type="submit">Adicionar Residência</button>
            </form>
            <div id="feedback"></div>
        </div>

        <div class="table-container">
            <h2>Residências Cadastradas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="residenciasTable">
                    <?php foreach ($residencias as $residencia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($residencia['Nome']); ?></td>
                            <td><?php echo htmlspecialchars($residencia['Endereco']); ?></td>
                            <td class="action-buttons">
                                <a href="/desenvolvimentoprojeto/residencias/select/<?php echo $residencia['Id']; ?>" class="button select-btn">Selecionar</a>
                                <a href="/desenvolvimentoprojeto/residencias/delete/<?php echo $residencia['Id']; ?>" class="button delete-btn" onclick="return confirm('Deseja deletar?');">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>