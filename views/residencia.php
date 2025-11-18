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
    
    <style>
        .action-buttons .button {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .action-buttons .select-btn {
            background-color: #28a745;
        }
        .action-buttons .select-btn:hover {
            background-color: #218838;
        }
        .action-buttons .delete-btn {
            background-color: #dc3545; 
        }
        .action-buttons .delete-btn:hover {
            background-color: #c82333;
        }
        .action-buttons .edit-btn {
            background-color: #ffc107; 
            color: #212529;
        }
        .action-buttons .edit-btn:hover {
            background-color: #e0a800;
        }
    </style>
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
                            
                            <a href="/desenvolvimentoprojeto/residencias/edit/<?php echo $residencia['Id']; ?>" class="button edit-btn">Alterar</a>
                            
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