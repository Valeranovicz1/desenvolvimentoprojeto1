document.addEventListener('DOMContentLoaded', () => {
    console.log("Página de medições carregada.");

    // --- ELEMENTOS DO DOM ---
    const form = document.getElementById('wifiForm');
    const tableBody = document.getElementById('wifiData');
    const recordIdInput = document.getElementById('recordId');
    const pageTitle = document.getElementById('pageTitle');

    // --- CARREGAR DADOS ---
    const residenciaAtiva = JSON.parse(localStorage.getItem('residenciaAtiva'));
    const comodoAtivoIndex = localStorage.getItem('comodoAtivoIndex');
    
    console.log("Residência Ativa:", residenciaAtiva);
    console.log("Índice do Cômodo Ativo:", comodoAtivoIndex);

    if (!residenciaAtiva || comodoAtivoIndex === null) {
        alert("Erro: Nenhum cômodo selecionado. Retornando...");
        window.location.href = 'cadastrar-residencia.html';
        return;
    }

    const storageKey = 'comodos_' + residenciaAtiva.nome.replace(/\s/g, '_');
    console.log("Chave de armazenamento (Storage Key):", storageKey);

    const comodosData = JSON.parse(localStorage.getItem(storageKey)) || [];
    console.log("Dados de todos os cômodos carregados:", comodosData);

    const comodoAtual = comodosData[comodoAtivoIndex];
    
    if (!comodoAtual) {
        alert("Erro: Cômodo não encontrado. Retornando...");
        window.location.href = 'cadastrar-comodo.html';
        return;
    }
    console.log("Cômodo Atual sendo editado:", comodoAtual);

    pageTitle.innerHTML = `Medições para: <span>${residenciaAtiva.nome} / ${comodoAtual.nome}</span>`;

    // Garante que 'medicoes' seja sempre um array
    if (!comodoAtual.medicoes) {
        comodoAtual.medicoes = [];
    }
    let data = comodoAtual.medicoes;

    // --- FUNÇÕES DE CRUD ---
    function renderTable() {
        tableBody.innerHTML = '';
        data.forEach((record, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${record.nivelSinal} dBm</td>
                <td>${record.velocidade} Mbps</td>
                <td>${record.interferencia}</td>
                <td class="action-buttons">
                    <button class="edit-btn" onclick="editRecord(${index})">Editar</button>
                    <button class="delete-btn" onclick="deleteRecord(${index})">Deletar</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    function saveData() {
        console.log("--- INICIANDO PROCESSO DE SALVAMENTO ---");
        console.log("1. Array de medições atual (antes de salvar):", data);

        comodoAtual.medicoes = data;
        comodosData[comodoAtivoIndex] = comodoAtual;

        console.log("2. Objeto completo que será salvo no localStorage:", comodosData);

        try {
            localStorage.setItem(storageKey, JSON.stringify(comodosData));
            console.log("3. SUCESSO! Dados salvos no localStorage.");
        } catch (e) {
            console.error("ERRO AO SALVAR NO LOCALSTORAGE:", e);
        }
        console.log("--- FIM DO PROCESSO DE SALVAMENTO ---");
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        console.log("Formulário de medição enviado.");
        const medicao = {
            nivelSinal: document.getElementById('nivelSinal').value,
            velocidade: document.getElementById('velocidade').value,
            interferencia: document.getElementById('interferencia').value
        };
        const id = recordIdInput.value;

        if (id) {
            console.log("Editando medição no índice:", id);
            data[id] = medicao;
        } else {
            console.log("Adicionando nova medição:", medicao);
            data.push(medicao);
        }

        saveData();
        renderTable();
        form.reset();
        recordIdInput.value = '';
    });

    window.editRecord = (index) => {
        const record = data[index];
        document.getElementById('nivelSinal').value = record.nivelSinal;
        document.getElementById('velocidade').value = record.velocidade;
        document.getElementById('interferencia').value = record.interferencia;
        recordIdInput.value = index;
    };

    window.deleteRecord = (index) => {
        if (confirm('Tem certeza que deseja deletar este registro?')) {
            data.splice(index, 1);
            saveData();
            renderTable();
        }
    };

    // --- INICIA A PÁGINA ---
    renderTable();
});