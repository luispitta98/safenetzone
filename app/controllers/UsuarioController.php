<?php

class UsuarioController {
    private $usuarioRepositorio;

    public function __construct() {
        $this->usuarioRepositorio = new UsuarioRepository();
    }

    public function verificarSessao() {
        session_start();
        if (isset($_SESSION['usuario_id'])) {
            return ["status" => "success", "mensagem" => "Usuário logado"];
        } else {
            return ["status" => "error", "mensagem" => "Usuário não está logado"];
        }
    }

    public function login($email, $senha) {
        session_start();

        $usuario = $this->usuarioRepositorio->encontrarPorEmail($email);
        if ($usuario && password_verify($senha, $usuario->getSenha())) {
            $_SESSION['usuario_id'] = $usuario->getId();
            return ["status" => "success", "mensagem" => "Login efetuado com Sucesso!"];
        } else {
            return ["status" => "error", "mensagem" => "Login inválido"];
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        return ["status" => "success", "mensagem" => "Logout efetuado com Sucesso!"];
    }

    public function adicionar($dados) {
        $nome = $dados['nome'] ?? null;
        $email = $dados['email'] ?? null;
        $senha = $dados['senha'] ?? null;

        if (!$nome || !$email || !$senha) {
            return ["status" => "error", "mensagem" => "Todos os campos são obrigatórios"];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["status" => "error", "mensagem" => "E-mail inválido"];
        }

        try {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $this->usuarioRepositorio->adicionar($nome, $email, $senhaHash);

            return ["status" => "success", "mensagem" => "Usuário adicionado"];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return ["status" => "error", "mensagem" => "Este e-mail já está cadastrado"];
            } else {
                return ["status" => "error", "mensagem" => "Erro ao salvar no banco: " . $e->getMessage()];
            }
        }
    }

    public function listar() {
        try {
            $usuarios = $this->usuarioRepositorio->listar();
            return ["status" => "success", "usuarios" => $usuarios];
        } catch (PDOException $e) {
            return ["status" => "error", "mensagem" => "Erro ao listar usuários: " . $e->getMessage()];
        }
    }

    public function remover($id) {
        if (!$id) {
            return ["status" => "error", "mensagem" => "ID é obrigatório"];
        }

        try {
            $this->usuarioRepositorio->remover($id);
            return ["status" => "success", "mensagem" => "Usuário removido"];
        } catch (PDOException $e) {
            return ["status" => "error", "mensagem" => "Erro ao remover: " . $e->getMessage()];
        }
    }
}
?>
