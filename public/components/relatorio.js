async function carregarRelatorios() {
    const conteudo = document.getElementById("conteudo");
    conteudo.innerHTML = `
        <div class="card">
            <h2 class="page-title">Relatórios de Acesso</h2>
            <div class="tabs">
                <ul class="tab-list">
                    <li class="tab-item active" data-target="sites">Sites Mais Acessados</li>
                    <li class="tab-item" data-target="ip">Acessos por IP</li>
                    <li class="tab-item" data-target="hora">Acessos por Hora</li>
                    <li class="tab-item" data-target="bloqueados">Tentativas Bloqueadas</li>
                    <li class="tab-item" data-target="conteudoPorTermo">Conteúdo por Termo</li>
                    <li class="tab-item" data-target="dia">Acessos por Dia</li>
                </ul>
            </div>
            <div id="relatorioConteudo" class="relatorio-conteudo">
                <p class="loading">Selecione um relatório acima para visualizar.</p>
            </div>
        </div>
    `;

    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', async () => {
            document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const tipo = tab.getAttribute('data-target');

            const container = document.getElementById('relatorioConteudo');

            if (tipo === 'conteudoPorTermo') {
                container.innerHTML = `
                    <div class="filtro-termo">
                        <label for="inputTermo">Buscar por termo:</label>
                        <input type="text" id="inputTermo" placeholder="Ex: arma, nudez, globo..." />
                        <button id="btnBuscarTermo">Buscar</button>
                    </div>
                    <div id="resultadoRelatorioTermo"></div>
                `;

                document.getElementById('btnBuscarTermo').addEventListener('click', async () => {
                    const termo = document.getElementById('inputTermo').value.trim();
                    await carregarRelatorioDinamico('conteudoPorTermo', termo);
                });
            } else {
                await carregarRelatorioDinamico(tipo);
            }
        });
    });
}

async function carregarRelatorioDinamico(tipo, termo = '') {
    const container = tipo === 'conteudoPorTermo'
        ? document.getElementById('resultadoRelatorioTermo')
        : document.getElementById('relatorioConteudo');

    container.innerHTML = `<p class="loading">Carregando relatório...</p>`;

    const config = {
        sites: {
            url: API_ROUTES.relatorioSitesMaisAcessados,
            colunas: ['URL', 'Acessos'],
            campos: ['url', 'acessos']
        },
        ip: {
            url: API_ROUTES.relatorioPorIp,
            colunas: ['IP', 'Acessos'],
            campos: ['ip', 'acessos']
        },
        hora: {
            url: API_ROUTES.relatorioPorHora,
            colunas: ['Hora', 'Total'],
            campos: ['hora', 'total']
        },
        conteudoPorTermo: {
            url: API_ROUTES.relatorioConteudoPorTermo,
            colunas: ['Hora', 'IP', 'URL', 'Mac'],
            campos: ['timestamp', 'ip', 'url', 'mac']
        },
        bloqueados: {
            url: API_ROUTES.relatorioBloqueados,
            colunas: ['Hora', 'IP', 'URL', 'Mac'],
            campos: ['timestamp', 'ip', 'url', 'mac']
        },
        dia: {
            url: API_ROUTES.relatorioPorDia,
            colunas: ['Data', 'Total'],
            campos: ['dia', 'total']
        }
    };

    const conf = config[tipo];
    if (!conf) {
        container.innerHTML = "<p class='loading'>Relatório desconhecido.</p>";
        return;
    }

    try {
        let res;

        if (tipo === 'conteudoPorTermo' && termo) {
            res = await fetch(conf.url, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ termo })
            });
        } else {
            res = await fetch(conf.url);
        }

        const data = await res.json();

        if (data.status !== "success" || !Array.isArray(data.dados)) {
            container.innerHTML = "<p class='loading'>Erro ao carregar relatório.</p>";
            return;
        }

        let html = "<table class='tabela-relatorio'><thead><tr>";
        conf.colunas.forEach(c => html += `<th>${c}</th>`);
        html += "</tr></thead><tbody>";

        data.dados.forEach(row => {
            html += "<tr>";
            conf.campos.forEach(campo => {
                let value = row[campo];
                if (campo === 'timestamp') {
                    value = formatarDataHora(value);
                }
                html += `<td>${value ?? '-'}</td>`;
            });
            html += "</tr>";
        });

        html += "</tbody></table>";
        container.innerHTML = html;

    } catch (err) {
        container.innerHTML = "<p class='loading'>Erro ao carregar relatório.</p>";
    }
}
