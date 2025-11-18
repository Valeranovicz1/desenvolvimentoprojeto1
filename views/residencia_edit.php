<?php
// views/residencia_edit_view.php

// Proteção contra acesso direto
if (!defined('APP_LOADED')) {
    die('Acesso direto não permitido.');
}
// A variável $residencia vem do index.php (rota GET /residencias/edit/{id})
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/desenvolvimentoprojeto/assets/css/styleresidencia.css"> 
    <title>Alterar Residência</title>
</head>
<body>

    <h1>Alterar Residência</h1>
    <div class="container">
        <div class="form-container" style="max-width: 600px; margin: auto;">
            
            <h2>Editando: <?php echo htmlspecialchars($residencia['Nome']); ?></h2>
            
            <form id="residenciaForm" action="/desenvolvimentoprojeto/residencias/update" method="POST">
                
                <input type="hidden" name="id" value="<?php echo $residencia['Id']; ?>">
                
                <div>
                    <label for="nomeResidencia">Nome da Residência:</label>
                    <input type="text" id="nomeResidencia" name="nomeResidencia" value="<?php echo htmlspecialchars($residencia['Nome']); ?>" required>
                </div>
                <div>
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($residencia['Endereco']); ?>" required>
                </div>
                <button type="submit">Salvar Alterações</button>
            </form>
            
            <a href="/desenvolvimentoprojeto/" class="nav-link" style="margin-top: 15px;">Cancelar</a>
        </div>
    </div>

</body>
</html>