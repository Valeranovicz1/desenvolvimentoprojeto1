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
    <link rel="stylesheet" href="/desenvolvimentoprojeto/assets/css/stylemedicao.css"> 
    <title>Análise WiFi - Medições</title>

    <style>
 
        .action-buttons .button {
            display: inline-block; padding: 5px 10px; margin: 2px;
            font-size: 12px; font-weight: bold; color: #fff;
            text-decoration: none; border-radius: 4px; border: none;
            cursor: pointer; text-align: center;
        }
        .action-buttons .edit-btn { 
            background-color: #ffc107;
            color: #212529; 
        }
        .action-buttons .delete-btn { background-color: #dc3545; }
        
        #btnSalvarMedicao, .nav-link {
            display: block;
            width: 100%; 
            padding: 10px;
            margin-top: 10px;
            
            color: #fff;
            text-decoration: none;
            text-align: center;
            border-radius: 4px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            box-sizing: border-box; 
            font-size: 14px; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
        }

        #btnSalvarMedicao {
            background-color: #007bff; 
        }
        #btnSalvarMedicao:hover {
            background-color: #0056b3;
        }

        .nav-link {
            background-color: #007bff; 
        }
        .nav-link:hover {
            background-color: #0056b3;
        }

        #btnCancelar {
            background-color: #6c757d;
            width: auto;
            display: none;
            margin-top: 10px;
            padding: 10px 15px;
            font-size: 14px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-weight: bold;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            color: #fff;
        }

    </style>
</head>
<body>

    <h1 id="pageTitle">Medições para: <span><?php echo htmlspecialchars($residencia_nome); ?> / <?php echo htmlspecialchars($comodo_nome); ?></span></h1>
    <div class="container">
        <div class="form-container">
            <h2 id="formMedicaoTitle">Cadastrar Medição</h2>
            
            <form id="medicaoForm" action="/desenvolvimentoprojeto/medicoes/add" method="POST">
                
                <input type="hidden" id="medicaoId" name="medicaoId">
                
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
                    <input type="number" id="interferencia" name="interferencia" required>
                </div>
                
                <button type="submit" id="btnSalvarMedicao">Salvar</button>
                <button type="button" id="btnCancelar" onclick="resetForm()">Cancelar</button>
            
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
                                
                                <a href="#formMedicaoTitle" class="button edit-btn" 
                                   onclick="editMedicao(
                                       '<?php echo $medicao['Id']; ?>',
                                       '<?php echo $medicao['Nivel_Sinal']; ?>',
                                       '<?php echo $medicao['Velocidade']; ?>',
                                       '<?php echo $medicao['Interferencia']; ?>'
                                   )">Alterar</a>
                                
                                <a href="/desenvolvimentoprojeto/medicoes/delete/<?php echo $medicao['Id']; ?>" class="button delete-btn" onclick="return confirm('Deseja deletar?');">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>

        const form = document.getElementById('medicaoForm');
        const formTitle = document.getElementById('formMedicaoTitle');
        const submitButton = document.getElementById('btnSalvarMedicao');
        const cancelButton = document.getElementById('btnCancelar');
        
        const medicaoIdInput = document.getElementById('medicaoId');
        const nivelSinalInput = document.getElementById('nivelSinal');
        const velocidadeInput = document.getElementById('velocidade');
        const interferenciaInput = document.getElementById('interferencia');
        
        const addAction = "/desenvolvimentoprojeto/medicoes/add";
        const updateAction = "/desenvolvimentoprojeto/medicoes/update";

        function editMedicao(id, sinal, velocidade, interferencia) {
            formTitle.innerText = 'Alterar Medição';
            submitButton.innerText = 'Salvar Alterações';
            cancelButton.style.display = 'inline-block';
            form.action = updateAction;
            medicaoIdInput.value = id;
            nivelSinalInput.value = sinal;
            velocidadeInput.value = velocidade;
            interferenciaInput.value = interferencia;
            
            window.scrollTo(0, 0); 
            nivelSinalInput.focus();
        }

        function resetForm() {
            formTitle.innerText = 'Cadastrar Medição';
            submitButton.innerText = 'Salvar';
            cancelButton.style.display = 'none';
            form.action = addAction;
            form.reset();
        }
    </script>
    
</body>
</html>