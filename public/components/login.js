function carregarLogin() {
    let conteudo = document.getElementById("conteudo");
    conteudo.innerHTML = `
        <div class="card login-card">
            <h2 class="page-title">Acesso ao Sistema</h2>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-input" placeholder="Digite seu e-mail">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" class="form-input" placeholder="Digite sua senha">
            </div>
            <button onclick="fazerLogin()" class="btn-primary">Entrar</button>
        </div>
    `;
}

async function fazerLogin() {
    let email = document.getElementById("email").value;
    let senha = document.getElementById("senha").value;

    let res = await fetch(API_ROUTES.login, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, senha }),
    });

    let data = await res.json();

    if (data.status === "success") {
        toastr.success("Logado!");
        document.getElementById("menuNavegacao").style.display = "block";
        navegar("relatorios");
    } else {
        toastr.error(data.mensagem);
    }
}
