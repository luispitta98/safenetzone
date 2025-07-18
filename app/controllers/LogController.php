<?php
class LogController {
    private $logRepositorio;

    public function __construct() {
        $this->logRepositorio = new LogRepository();
    }

    public function adicionar($log) {
        $this->logRepositorio->adicionar($log);
    }

    public function listar($limit = 100) {
        $logs = $this->logRepositorio->listar($limit);
        return [
            "status" => "success",
            "logs" => array_map(fn($l) => $l->jsonSerialize(), $logs)
        ];
    }

    public function limparTodos() {
        $ok = $this->logRepositorio->limparTodos();
        return $ok
            ? ["status" => "success", "mensagem" => "Todos os logs foram apagados."]
            : ["status" => "error", "mensagem" => "Erro ao limpar os logs."];
    }

    public function relatorioSitesMaisAcessados() {
        $dados = $this->logRepositorio->sitesMaisAcessados();
        return ["status" => "success", "dados" => $dados];
    }

    public function relatorioAcessosPorIp() {
        $dados = $this->logRepositorio->acessosPorIp();
        return ["status" => "success", "dados" => $dados];
    }

    public function relatorioConteudoPorTermo( $palavra ) {
        $dados = $this->logRepositorio->termoSensivel( $palavra );
        return ["status" => "success", "dados" => $dados];
    }

    public function relatorioAcessosPorHora() {
        $dados = $this->logRepositorio->acessosPorHora();
        return ["status" => "success", "dados" => $dados];
    }

    public function relatorioAcessosBloqueados() {
        $dados = $this->logRepositorio->acessosBloqueados();
        return ["status" => "success", "dados" => $dados];
    }

    public function relatorioAcessosPorDia() {
        $dados = $this->logRepositorio->acessosPorDia();
        return ["status" => "success", "dados" => $dados];
    }

}
