            
            
            document.addEventListener('DOMContentLoaded', () => {
            const residenciaForm = document.getElementById('residenciaForm');
            const residenciasTable = document.getElementById('residenciasTable');
            const feedbackEl = document.getElementById('feedback');
            
            // Agora vamos usar uma chave para guardar um ARRAY de residências
            const storageKey = 'residenciasSalvas';

            // Carrega as residências salvas ou inicia um array vazio
            let residencias = JSON.parse(localStorage.getItem(storageKey)) || [];

            // Função para salvar o array de residências no localStorage
            function saveData() {
                localStorage.setItem(storageKey, JSON.stringify(residencias));
            }

            // Função para renderizar (desenhar) a tabela de residências
            function renderTable() {
                residenciasTable.innerHTML = ''; // Limpa a tabela antes de redesenhar
                if (residencias.length === 0) {
                    residenciasTable.innerHTML = '<tr><td colspan="3" style="text-align:center;">Nenhuma residência cadastrada.</td></tr>';
                    return;
                }
                
                residencias.forEach((residencia, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${residencia.nome}</td>
                        <td>${residencia.endereco}</td>
                        <td class="action-buttons">
                            <button class="select-btn" onclick="selecionarResidencia(${index})">Selecionar</button>
                            <button class="delete-btn" onclick="deletarResidencia(${index})">Deletar</button>
                        </td>
                    `;
                    residenciasTable.appendChild(row);
                });
            }

            // Ação do formulário para ADICIONAR uma nova residência
            residenciaForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const nome = document.getElementById('nomeResidencia').value;
                const endereco = document.getElementById('endereco').value;

                // Adiciona a nova residência ao array
                residencias.push({ nome, endereco });
                
                // Salva o array atualizado
                saveData();
                
                // Redesenha a tabela para mostrar a nova entrada
                renderTable();
                
                // Limpa o formulário
                residenciaForm.reset();
            });

            // Função para SELECIONAR uma residência e ir para a próxima página
            window.selecionarResidencia = (index) => {
                const residenciaSelecionada = residencias[index];
                
                // Salva a residência escolhida como "ativa" para as outras páginas usarem
                localStorage.setItem('residenciaAtiva', JSON.stringify(residenciaSelecionada));
                
                feedbackEl.textContent = `Residência "${residenciaSelecionada.nome}" selecionada! Redirecionando...`;

                setTimeout(() => {
                    window.location.href = 'comodo.html';
                }, 1500);
            };

            // Função para DELETAR uma residência
            window.deletarResidencia = (index) => {
                const nomeResidencia = residencias[index].nome;
                if (confirm(`Tem certeza que deseja deletar a residência "${nomeResidencia}"?`)) {
                    // Remove a residência do array pelo seu índice
                    residencias.splice(index, 1);
                    
                    // Salva o array modificado
                    saveData();
                    
                    // Redesenha a tabela
                    renderTable();
                }
            };

            // Chama a função renderTable() assim que a página carrega para mostrar os dados salvos
            renderTable();
        });
