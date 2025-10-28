<?php
// views/comodo_view.php
if (!defined('APP_LOADED')) {
    die('Acesso direto não permitido.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/desenvolvimentoprojeto/assets/css/stylecomodo.css"> 
    <title>Análise WiFi - Cômodos</title>
</head>
<body>

    <h1>Gerenciar Cômodos da Residência: <span><?php echo htmlspecialchars($residencia_nome); ?></span></h1>
    <div class="container">
        <div class="form-container">
            <h2 id="formComodoTitle">Cadastrar Novo Cômodo</h2>
            <form id="comodoForm" action="/desenvolvimentoprojeto/comodos/add" method="POST">
                <div>
                    <label for="comodoNome">Nome do Cômodo:</label>
                    <input type="text" id="comodoNome" name="comodoNome" required>
                </div>
                <button type="submit">Salvar Cômodo</button>
            </form>
            <a href="/desenvolvimentoprojeto/" class="nav-link">Trocar de Residência</a>
        </div>
        <div class="table-container">
            <h2>Cômodos Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Cômodo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="comodosTable">
                    <?php foreach ($comodos as $comodo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($comodo['Nome']); ?></td>
                            <td class="action-buttons">
                                <a href="/desenvolvimentoprojeto/comodos/select/<?php echo $comodo['Id']; ?>" class="button select-btn">Criar/Ver Medições</a>
                                
                                <a href="/desenvolvimentoprojeto/comodos/delete/<?php echo $comodo['Id']; ?>" class="button delete-btn" onclick="return confirm('Deseja deletar?');">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>