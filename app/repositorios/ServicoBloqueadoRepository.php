<?php
class ServicoBloqueadoRepository extends BaseRepository {
    private const TABELA = 'servico_bloqueado';

    private function transformarEmObjeto($data) {
        if (!$data) return null;
        return new ServicoBloqueado(
            $data['id'],
            $data['whatsapp_bloqueado'],
            $data['discord_bloqueado'],
            $data['tiktok_bloqueado']
        );
    }

    public function listar() {
        $data = $this->fetch("SELECT * FROM " . self::TABELA . " LIMIT 1");
        return $this->transformarEmObjeto($data);
    }

    public function atualizarServico($servico, $status) {
        $coluna = $servico . '_bloqueado';
        $sql = "UPDATE " . self::TABELA . " SET $coluna = ? WHERE id = 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status ? 1 : 0]);
    }
}
?>
