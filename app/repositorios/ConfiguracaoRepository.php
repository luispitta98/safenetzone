<?php
class ConfiguracaoRepository extends BaseRepository {
    private const TABELA = 'configuracao';

    private function transformarEmObjeto($data) {
        if (!$data) return null;
        return new Configuracao(
            $data['id'],
            $data['nome_rede'],
            $data['senha_rede']
        );
    }

    public function listar() {
        $data = $this->fetch("SELECT * FROM " . self::TABELA . " LIMIT 1");
        return $this->transformarEmObjeto($data);
    }

    public function atualizarCampo($campo, $valor) {
        $sql = "UPDATE " . self::TABELA . " SET $campo = ? WHERE id = 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$valor]);
    }
}
?>
