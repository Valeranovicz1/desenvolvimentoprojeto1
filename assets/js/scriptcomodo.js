document.addEventListener('DOMContentLoaded', () => {
            let comodosData = [];
            const comodoForm = document.getElementById('comodoForm');
            const comodosTable = document.getElementById('comodosTable');

            const residenciaAtiva = JSON.parse(localStorage.getItem('residenciaAtiva'));
            if (!residenciaAtiva) {
                window.location.href = 'residencia.html';
                return;
            }

            document.getElementById('nomeResidencia').textContent = residenciaAtiva.nome;
            const storageKey = 'comodos_' + residenciaAtiva.nome.replace(/\s/g, '_');
            comodosData = JSON.parse(localStorage.getItem(storageKey)) || [];

            function saveData() {
                localStorage.setItem(storageKey, JSON.stringify(comodosData));
            }

            function renderComodosTable() {
                comodosTable.innerHTML = '';
                comodosData.forEach((comodo, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${comodo.nome}</td>
                        <td class="action-buttons">
                            <button class="view-btn" onclick="irParaMedicoes(${index})">Criar/Visualizar Medições</button>
                            <button class="edit-btn" onclick="editComodo(${index})">Editar Nome</button>
                            <button class="delete-btn" onclick="deleteComodo(${index})">Deletar</button>
                        </td>
                    `;
                    comodosTable.appendChild(row);
                });
            }

            comodoForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const comodoNome = document.getElementById('comodoNome').value;
                const comodoId = document.getElementById('comodoId').value;

                if (comodoId) {
                    comodosData[comodoId].nome = comodoNome;
                } else {
                    comodosData.push({ nome: comodoNome, medicoes: [] });
                }
                saveData();
                renderComodosTable();
                comodoForm.reset();
                document.getElementById('comodoId').value = '';
                document.getElementById('formComodoTitle').textContent = 'Cadastrar Novo Cômodo';
                document.getElementById('btnSalvarComodo').textContent = 'Salvar Cômodo';
            });

            window.editComodo = (index) => {
                const comodo = comodosData[index];
                document.getElementById('comodoNome').value = comodo.nome;
                document.getElementById('comodoId').value = index;
                document.getElementById('formComodoTitle').textContent = 'Editar Nome do Cômodo';
                document.getElementById('btnSalvarComodo').textContent = 'Atualizar Nome';
                window.scrollTo(0, 0);
            };

            window.deleteComodo = (index) => {
                if (confirm(`Tem certeza que deseja deletar o cômodo "${comodosData[index].nome}"?`)) {
                    comodosData.splice(index, 1);
                    saveData();
                    renderComodosTable();
                }
            };
            
            // FUNÇÃO PRINCIPAL: Salva o índice do cômodo e redireciona
            window.irParaMedicoes = (index) => {
                // Salva no localStorage qual cômodo estamos editando
                localStorage.setItem('comodoAtivoIndex', index);
                // Redireciona para a nova página
                window.location.href = 'medicao.html';
            };

            renderComodosTable();
        });