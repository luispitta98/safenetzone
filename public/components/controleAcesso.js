const estadoLocalBloqueios = {
    whatsapp: false,
    discord: false,
    tiktok: false
};

const estadoLocalSites = [];

async function carregarBloqueios() {
    let conteudo = document.getElementById("conteudo");
    conteudo.innerHTML = `
        <div class="card">
            <h2 class="page-title">Bloqueios e Restrições</h2>

            <div class="bloqueio-wrapper">
                <div class="bloqueio-coluna">
                    <h3 class="section-title">Bloquear Serviços</h3>
                    <div class="toggle-group">
                        ${gerarToggle("WhatsApp", "whatsappToggle")}
                        ${gerarToggle("Discord", "discordToggle")}
                        ${gerarToggle("TikTok", "tiktokToggle")}
                    </div>
                </div>

                <div class="bloqueio-coluna">
                    <h3 class="section-title">Sites Proibidos</h3>
                    <div class="form-inline" style="margin-bottom: 10px;">
                        <input type="text" id="novoSite" placeholder="Domínio" class="form-input">
                        <button id="addSite" class="btn-primary">Adicionar</button>
                    </div>
                    <ul id="listaSites" class="site-list"></ul>
                </div>
            </div>

            <div style="margin-top: 30px; text-align: center;">
                <button id="btnAplicarBloqueios" class="btn-primary">Aplicar Bloqueios</button>
            </div>
        </div>
    `;

    await carregarEstadoServicos();
    await carregarEstadoSites();

    document.getElementById('whatsappToggle').addEventListener('change', async (e) => {
        estadoLocalBloqueios.whatsapp = e.target.checked;
        await atualizarServico('whatsapp', e.target.checked);
    });
    document.getElementById('discordToggle').addEventListener('change', async (e) => {
        estadoLocalBloqueios.discord = e.target.checked;
        await atualizarServico('discord', e.target.checked);
    });
    document.getElementById('tiktokToggle').addEventListener('change', async (e) => {
        estadoLocalBloqueios.tiktok = e.target.checked;
        await atualizarServico('tiktok', e.target.checked);
    });

    document.getElementById('btnAplicarBloqueios').addEventListener('click', aplicarBloqueios);
    document.getElementById('addSite').addEventListener('click', adicionarSite);
}

async function carregarEstadoServicos() {
    const res = await fetch(API_ROUTES.servicos);
    const data = await res.json();
    if (data.status === 'success') {
        const c = data.servicos;
        estadoLocalBloqueios.whatsapp = c.whatsappBloqueado;
        estadoLocalBloqueios.discord = c.discordBloqueado;
        estadoLocalBloqueios.tiktok = c.tiktokBloqueado;

        document.getElementById('whatsappToggle').checked = c.whatsappBloqueado;
        document.getElementById('discordToggle').checked = c.discordBloqueado;
        document.getElementById('tiktokToggle').checked = c.tiktokBloqueado;
    }
}

async function carregarEstadoSites() {
    const res = await fetch(API_ROUTES.sites);
    const data = await res.json();
    if (data.status === 'success') {
        estadoLocalSites.length = 0;
        data.sites.forEach(s => estadoLocalSites.push({ id: s.id, dominio: s.dominio }));
        renderizarSites();
    }
}

function gerarToggle(label, id) {
    return `
        <div class="switch-label">
            <span>${label}</span>
            <label class="switch">
                <input type="checkbox" id="${id}">
                <span class="slider"></span>
            </label>
        </div>
    `;
}

function renderizarSites() {
    const ul = document.getElementById('listaSites');
    ul.innerHTML = '';
    estadoLocalSites.forEach((s, idx) => {
        ul.innerHTML += `<li>${s.dominio} <button onclick="removerSite(${s.id})">Remover</button></li>`;
    });
}

async function adicionarSite() {
    const input = document.getElementById('novoSite');
    const dominio = input.value.trim().toLowerCase();
    if (!dominio) return toastr.warning('Informe o domínio');
    if (estadoLocalSites.some(s => s.dominio === dominio)) return toastr.info('Já adicionado');

    const res = await fetch(API_ROUTES.sites, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ dominio })
    });

    const data = await res.json();
    if (data.status === 'success') {
        toastr.success(data.mensagem || 'Adicionado!');
        input.value = '';
        carregarEstadoSites();
    } else {
        toastr.error(data.mensagem || 'Erro ao adicionar site');
    }
}

async function removerSite(id) {
    const res = await fetch(`${API_ROUTES.sites}?id=${id}`, { method: 'DELETE' });
    const data = await res.json();
    if (data.status === 'success') {
        toastr.success('Removido');
        carregarEstadoSites();
    } else {
        toastr.error(data.mensagem || 'Erro ao remover');
    }
}

async function atualizarServico(servico, ativo) {
    const payload = { [servico]: ativo };
    const res = await fetch(API_ROUTES.servicos, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });

    const data = await res.json();
    if (data.status !== 'success') {
        toastr.error(`Erro ao atualizar ${servico}`);
    }
}

async function aplicarBloqueios() {
    const payload = {
        ...estadoLocalBloqueios,
        sites: estadoLocalSites.map(site => site.dominio)
    };

    const res = await fetch(API_ROUTES.atualizarBloqueios, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });

    const data = await res.json();
    if (data.status === 'success') {
        toastr.success('Bloqueios aplicados com sucesso!');
    } else {
        toastr.error(data.mensagem || 'Erro ao aplicar bloqueios.');
    }
}