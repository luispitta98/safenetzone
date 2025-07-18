<?php
class SiteProibidoRepository extends BaseRepository {
    private const TABELA = "site_proibido";

    private function transformarEmObjeto($data) {
        if (!$data) return null;
        return new SiteProibido($data['id'], $data['dominio']);
    }

    public function adicionar($dominio) {
        $this->execute("INSERT INTO " . self::TABELA . " (dominio) VALUES (?)", [$dominio]);
        return new SiteProibido($this->pdo->lastInsertId(), $dominio);
    }

    public function listar() {
        $data = $this->fetchAll("SELECT * FROM " . self::TABELA);
        return array_map([$this, 'transformarEmObjeto'], $data);
    }

    public function remover($id) {
        return $this->execute("DELETE FROM " . self::TABELA . " WHERE id = ?", [$id]);
    }
}
?>
