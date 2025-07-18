<?php
class LogRepository extends BaseRepository {
    private const TABELA = 'logs_squid';

    private function transformarEmObjeto($data) {
        if (!$data) return null;

        return new Log(
            $data['id'],
            $data['timestamp'],
            $data['tempo'],
            $data['ip'],
            $data['codigo'],
            $data['tamanho'],
            $data['metodo'],
            $data['url'],
            $data['usuario'],
            $data['hierarquia'],
            $data['conteudo_tipo'],
            $data['mac'],
            $data['bloqueio_tipo'],
        );
    }

    public function adicionar($dados) {
        $sql = "INSERT INTO " . self::TABELA . "
            (timestamp, tempo, ip, codigo, tamanho, metodo, url, usuario, hierarquia, conteudo_tipo, mac, bloqueio_tipo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $dados['timestamp'],
            $dados['tempo'],
            $dados['ip'],
            $dados['codigo'],
            $dados['tamanho'],
            $dados['metodo'],
            $dados['url'],
            $dados['usuario'],
            $dados['hierarquia'],
            $dados['conteudo_tipo'],
            $dados['mac'],
            $dados['bloqueio_tipo'],
        ]);
    }

    public function listar($limite = 100) {
        $limite = is_numeric($limite) ? intval($limite) : 100;
        $data = $this->fetchAll("SELECT * FROM " . self::TABELA . " ORDER BY id DESC LIMIT $limite");
        return array_map([$this, 'transformarEmObjeto'], $data);
    }

    public function limparTodos() {
        return $this->execute("DELETE FROM " . self::TABELA);
    }

    public function sitesMaisAcessados($limite = 10) {
        $limite = intval($limite);
        $sql = "SELECT url, COUNT(*) AS acessos
                FROM " . self::TABELA . "
                GROUP BY url
                ORDER BY acessos DESC
                LIMIT $limite";
        return $this->fetchAll($sql);
    }

    public function acessosPorIp($limite = 10) {
        $limite = intval($limite);
        $sql = "SELECT ip, COUNT(*) AS acessos
                FROM " . self::TABELA . "
                GROUP BY ip
                ORDER BY acessos DESC
                LIMIT $limite";
        return $this->fetchAll($sql);
    }

    public function termoSensivel($termo) {
        $sql = "SELECT * FROM " . self::TABELA . " WHERE url LIKE ? ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$termo%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function acessosPorHora() {
        $sql = "SELECT FROM_UNIXTIME(timestamp, '%H:00') AS hora, COUNT(*) AS total
                FROM " . self::TABELA . "
                GROUP BY hora
                ORDER BY hora";
        return $this->fetchAll($sql);
    }

    public function acessosBloqueados() {
        $sql = "SELECT * FROM " . self::TABELA . " WHERE bloqueio_tipo IS NOT NULL ORDER BY id DESC";
        return $this->fetchAll($sql);
    }

    public function acessosPorDia() {
        $sql = "SELECT DATE(FROM_UNIXTIME(timestamp)) AS dia, COUNT(*) AS total
                FROM " . self::TABELA . "
                GROUP BY dia
                ORDER BY dia DESC";
        return $this->fetchAll($sql);
    }
}
?>
