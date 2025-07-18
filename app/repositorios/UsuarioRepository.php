<?php
class UsuarioRepository extends BaseRepository {
    private const TABELA = "usuario";

    private function transformarEmObjeto($data) {
        if (!$data) return null;
        return new Usuario($data['id'], $data['nome'], $data['email'], $data['senha']);
    }

    public function adicionar($nome, $email, $senhaHash) {
        $this->execute(
            "INSERT INTO " . self::TABELA . " (nome, email, senha) VALUES (?, ?, ?)",
            [$nome, $email, $senhaHash]
        );
        return new Usuario($this->pdo->lastInsertId(), $nome, $email, $senhaHash);
    }

    public function listar() {
        $dados = $this->fetchAll("SELECT * FROM " . self::TABELA);
        return array_map([$this, 'transformarEmObjeto'], $dados);
    }

    public function encontrarPorEmail($email) {
        $data = $this->fetch("SELECT * FROM " . self::TABELA . " WHERE email = ?", [$email]);
        return $this->transformarEmObjeto($data);
    }

    public function obterComId($id) {
        $data = $this->fetch("SELECT * FROM " . self::TABELA . " WHERE id = ?", [$id]);
        return $this->transformarEmObjeto($data);
    }

    public function remover($id) {
        return $this->execute("DELETE FROM " . self::TABELA . " WHERE id = ?", [$id]);
    }
}
?>
