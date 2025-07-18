

async function carregarUsuarios() {
    let conteudo = document.getElementById("conteudo");
    conteudo.innerHTML = `
        <h2>Gerenciar Usuários</h2>
        <input type="text" id="nome" placeholder="Nome">
        <input type="email" id="email" placeholder="E-mail">
        <input type="password" id="senha" placeholder="Senha">
        <button onclick="adicionarUsuario()">Adicionar Usuário</button>
        <ul id="listaUsuarios"></ul>
    `;

    listarUsuarios();
}

async function listarUsuarios() {
    try {
        let res = await fetch(`${API_ROUTES.usuarios}`);
        let data = await res.json();

        if (data.status !== "success") {
            toastr.error(data.mensagem || "Erro ao listar usuários.");
            return;
        }

        let lista = document.getElementById("listaUsuarios");
        lista.innerHTML = "";

        data.usuarios.forEach(usuario => {
            lista.innerHTML += `
                <li>
                    ${usuario.nome} (${usuario.email})
                    <button onclick="removerUsuario(${usuario.id})">Remover</button>
                </li>`;
        });
    } catch (error) {
        toastr.error("Erro ao carregar usuários.");
    }
}

async function adicionarUsuario() {
    let nome = document.getElementById("nome").value.trim();
    let email = document.getElementById("email").value.trim();
    let senha = document.getElementById("senha").value;

    if (!nome || !email || !senha) {
        toastr.warning("Preencha todos os campos!");
        return;
    }

    try {
        let res = await fetch(`${API_ROUTES.usuarios}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nome, email, senha })
        });

        let data = await res.json();

        if (data.status === "success") {
            toastr.success(data.mensagem || "Usuário adicionado!");
            document.getElementById("nome").value = "";
            document.getElementById("email").value = "";
            document.getElementById("senha").value = "";
        } else {
            toastr.error(data.mensagem || "Erro ao adicionar usuário.");
        }
    } catch (error) {
        toastr.error("Erro ao adicionar usuário.");
    }

    listarUsuarios();
}

async function removerUsuario(id) {
    if (!confirm("Tem certeza que deseja remover este usuário?")) return;

    try {
        let res = await fetch(`${API_ROUTES.usuarios}?id=${id}`, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" }
        });

        let data = await res.json();

        if (data.status === "success") {
            toastr.success(data.mensagem || "Usuário removido!");
        } else {
            toastr.error(data.mensagem || "Erro ao remover usuário.");
        }
    } catch (error) {
        toastr.error("Erro ao remover usuário.");
    }

    listarUsuarios();
}
