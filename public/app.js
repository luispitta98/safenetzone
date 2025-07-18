const API_BASE = "/rede-segura/routes/web.php";

const API_ROUTES = {
    configuracao: `${API_BASE}/configuracao`,
    atualizarBloqueios: `${API_BASE}/atualizarBloqueios`,
    sites: `${API_BASE}/sites`,
    servicos: `${API_BASE}/servicos`,
    logs: `${API_BASE}/logs`,
    usuarios: `${API_BASE}/usuarios`,
    login: `${API_BASE}/login`,
    logout: `${API_BASE}/logout`,
    verificarSessao: `${API_BASE}/verificar_sessao`,
    relatorioSitesMaisAcessados: `${API_BASE}/relatorio_sites_mais_acessados`,
    relatorioPorIp: `${API_BASE}/relatorio_por_ip`,
    relatorioPorHora: `${API_BASE}/relatorio_por_hora`,
    relatorioBloqueados: `${API_BASE}/relatorio_bloqueados`,
    relatorioConteudoPorTermo: `${API_BASE}/relatorio_conteudo_por_termo`,
    relatorioPorDia: `${API_BASE}/relatorio_por_dia`,
};

function navegar(tela) {
    let conteudo = document.getElementById("conteudo");
    conteudo.innerHTML = "";
    document.getElementById("conteudoLista").innerHTML = "";

    switch (tela) {
        case "login":
            carregarLogin();
            break;
        case "usuarios":
            carregarUsuarios();
            break;
        case "configuracoes":
            carregarConfiguracoesRede();
            break;
        case "bloqueios":
            carregarBloqueios();
            break;
        case "relatorios":
            carregarRelatorios();
            break;
        default:
            conteudo.innerHTML = "<h2>Erro: Página não encontrada</h2>";
    }

}

async function logout() {
    await fetch(API_ROUTES.logout, {
        method: "POST",
    });
    document.getElementById("menuNavegacao").style.display = "none";
    navegar("login");
}

async function verificarLogin() {
    let res = await fetch(API_ROUTES.verificarSessao);
    let data = await res.json();

    if (data.status === "success") {
        document.getElementById("menuNavegacao").style.display = "block";
        navegar("configuracoes");
    } else {
        document.getElementById("menuNavegacao").style.display = "none";
        carregarLogin();
    }
}

function formatarDataHora(segundos) {
    const data = new Date(segundos * 1000);
    return data.toLocaleString('pt-BR'); // ex: "17/07/2025 14:30:12"
}

// Carregar automaticamente a tela de login ao iniciar
document.addEventListener("DOMContentLoaded", verificarLogin);
