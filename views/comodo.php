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
    <link rel="stylesheet" href="/desenvolvimentoprojeto/assets/css/stylecomodo.css"> 
    <title>Análise WiFi - Cômodos</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .chart-container {
            width: 90%; max-width: 900px; margin: 20px auto;
            padding: 20px; background: #fff; border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .action-buttons .button {
            display: inline-block; padding: 5px 10px; margin: 2px;
            font-size: 12px; font-weight: bold; color: #fff;
            text-decoration: none; border-radius: 4px; border: none;
            cursor: pointer; text-align: center;
        }
        .action-buttons .select-btn { background-color: #28a745; }
        .action-buttons .delete-btn { background-color: #dc3545; }
        .action-buttons .edit-btn { 
            background-color: #ffc107;
            color: #212529; 
        }

        #btnSalvarComodo, .nav-link {
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

        #btnSalvarComodo {
            background-color: #007bff; 
        }
        #btnSalvarComodo:hover {
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

    <h1>Gerenciar Cômodos da Residência: <span><?php echo htmlspecialchars($residencia_nome); ?></span></h1>

    <div class="container">
        <div class="form-container">
            <h2 id="formComodoTitle">Cadastrar Novo Cômodo</h2>
            
            <form id="comodoForm" action="/desenvolvimentoprojeto/comodos/add" method="POST">
                
                <input type="hidden" id="comodoId" name="comodoId">
                
                <div>
                    <label for="comodoNome">Nome do Cômodo:</label>
                    <input type="text" id="comodoNome" name="comodoNome" required>
                </div>
                
                <button type="submit" id="btnSalvarComodo">Salvar Cômodo</button>
                <button type="button" id="btnCancelar" onclick="resetForm()">Cancelar</button>
            
            </form>
            
            <a href="/desenvolvimentoprojeto/" class="nav-link">Trocar de Residência</a>
        </div>
        <div class="table-container">
            <h2>Cômodos Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Cômodo</th>
                        <th>Sinal Médio</th>
                        <th>Veloc. Média</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="comodosTable">
                    <?php foreach ($comodos as $comodo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($comodo['Nome']); ?></td>
                            <td><?php echo $comodo['avg_sinal'] ? number_format($comodo['avg_sinal'], 0) . ' dBm' : 'N/A'; ?></td>
                            <td><?php echo $comodo['avg_velocidade'] ? number_format($comodo['avg_velocidade'], 2) . ' Mbps' : 'N/A'; ?></td>
                            
                            <td class="action-buttons">
                                <a href="/desenvolvimentoprojeto/comodos/select/<?php echo $comodo['Id']; ?>" class="button select-btn">Criar/Ver Medições</a>
                                <a href="#formComodoTitle" onclick="editComodo(<?php echo $comodo['Id']; ?>, '<?php echo htmlspecialchars($comodo['Nome']); ?>')" class="button edit-btn">Alterar</a>
                                <a href="/desenvolvimentoprojeto/comodos/delete/<?php echo $comodo['Id']; ?>" class="button delete-btn" onclick="return confirm('Deseja deletar?');">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="graficoMedicoes"></canvas>
    </div>

    <script>

        const comodosData = <?php echo json_encode($comodos); ?>;

        const labels = [];
        const sinalData = [];
        const velocidadeData = [];
        const interferenciaData = [];

        comodosData.forEach(comodo => {
            labels.push(comodo.Nome);
            sinalData.push(comodo.avg_sinal || 0);
            velocidadeData.push(comodo.avg_velocidade || 0);
            interferenciaData.push(comodo.avg_interferencia || 0);
        });

        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Nível de Sinal (dBm)',
                    data: sinalData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)', 
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Velocidade (Mbps)',
                    data: velocidadeData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)', 
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Interferência (0-10)',
                    data: interferenciaData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)', 
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Média das Medições por Cômodo'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        const ctx = document.getElementById('graficoMedicoes').getContext('2d');
        new Chart(ctx, config);
    </script>
    
    <script>

        const form = document.getElementById('comodoForm');
        const formTitle = document.getElementById('formComodoTitle');
        const submitButton = document.getElementById('btnSalvarComodo');
        const cancelButton = document.getElementById('btnCancelar');
        const comodoNomeInput = document.getElementById('comodoNome');
        const comodoIdInput = document.getElementById('comodoId');
        const addAction = "/desenvolvimentoprojeto/comodos/add";
        const updateAction = "/desenvolvimentoprojeto/comodos/update";

        function editComodo(id, nome) {

            formTitle.innerText = 'Alterar Nome do Cômodo';
            submitButton.innerText = 'Salvar Alterações';
            cancelButton.style.display = 'inline-block'; 
    
            form.action = updateAction;

            comodoNomeInput.value = nome;
            comodoIdInput.value = id; 
            comodoNomeInput.focus();
        }

        function resetForm() {

            formTitle.innerText = 'Cadastrar Novo Cômodo';
            submitButton.innerText = 'Salvar Cômodo';
            cancelButton.style.display = 'none'; 
            
            form.action = addAction;

            form.reset();
        }
    </script>

</body>
</html>