async function salvarRede() {
    const nome = document.getElementById('nomeRede').value;
    const senha = document.getElementById('senhaRede').value;

    const res = await fetch(API_ROUTES.configuracao, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nome_rede: nome, senha_rede: senha })
    });

    const data = await res.json();
    if (data.status === 'success') {
        alert('Configurações salvas com sucesso!');
    } else {
        alert('Erro ao salvar configurações.');
    }
}

async function carregarConfiguracoesRede() {
    let conteudo = document.getElementById("conteudo");
    conteudo.innerHTML = `
        <div class="card">
            <h2 class="page-title">Configurações da Rede</h2>

            <div class="form-group">
                <label for="nomeRede">Nome da Rede</label>
                <input type="text" id="nomeRede" class="form-input">
            </div>

            <div class="form-group">
                <label for="senhaRede">Senha da Rede</label>
                <input type="password" id="senhaRede" class="form-input">
            </div>

            <button id="salvarRede" class="btn-primary">Salvar</button>

            <hr>

            <h3 style="text-align: center; margin-top: 30px;">QR Code de Conexão</h3>
            <div id="qrcode" style="display: flex; justify-content: center; margin: 20px 0;"></div>

            <div id="passoAPasso" style="margin-top: 30px;"></div>
        </div>
    `;

    const res = await fetch(API_ROUTES.configuracao);
    const data = await res.json();

    if (data.status === 'success') {
        const nome = data.configuracao.nomeRede;
        const senha = data.configuracao.senhaRede;

        document.getElementById('nomeRede').value = nome;
        document.getElementById('senhaRede').value = senha;

        const qrContent = `WIFI:T:WPA;S:${nome};P:${senha};;`;
        new QRCode(document.getElementById("qrcode"), {
            text: qrContent,
            width: 200,
            height: 200
        });

        document.getElementById("passoAPasso").innerHTML = `
            <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
                <div style="flex: 1; min-width: 280px; max-width: 400px; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
                    <h4 style="margin-bottom: 10px; color: #333;">Para Android:</h4>
                    <ol style="padding-left: 20px; margin-bottom: 0;">
                        <li>Vá em <strong>Ajustes &gt; Wi-Fi</strong></li>
                        <li>Conecte-se à rede: <strong>${nome}</strong></li>
                        <li>Toque em <strong>"Modificar Rede"</strong></li>
                        <li>Role para baixo e selecione <strong>"Proxy Manual"</strong></li>
                        <li>Configure:
                            <ul>
                                <li>Host do proxy: <strong>192.168.10.1</strong></li>
                                <li>Porta do proxy: <strong>3128</strong></li>
                            </ul>
                        </li>
                        <li>Salve as configurações</li>
                    </ol>
                </div>

                <div style="flex: 1; min-width: 280px; max-width: 400px; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
                    <h4 style="margin-bottom: 10px; color: #333;">Para iPhone (iOS):</h4>
                    <ol style="padding-left: 20px; margin-bottom: 0;">
                        <li>Vá em <strong>Ajustes &gt; Wi-Fi</strong></li>
                        <li>Conecte-se à rede: <strong>${nome}</strong></li>
                        <li>Toque no ícone <strong>ⓘ</strong> ao lado do nome da rede</li>
                        <li>Role até a seção <strong>"HTTP Proxy"</strong></li>
                        <li>Selecione <strong>"Manual"</strong></li>
                        <li>Configure:
                            <ul>
                                <li>Servidor: <strong>192.168.10.1</strong></li>
                                <li>Porta: <strong>3128</strong></li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>
        `;
    }

    document.getElementById('salvarRede').addEventListener('click', salvarRede);
}
