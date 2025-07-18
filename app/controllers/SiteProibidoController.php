<?php

class SiteProibidoController {
    private $siteRepositorio;

    public function __construct() {
        $this->siteRepositorio = new SiteProibidoRepository();
    }

    public function adicionar($dados) {
        $dominio = $dados['dominio'] ?? null;
    
        if (!$dominio) {
            return (["status" => "error", "mensagem" => "O domínio é obrigatório"]);
            return;
        }
    
        try {
            $this->siteRepositorio->adicionar($dominio);
            return (["status" => "success", "mensagem" => "Site adicionado"]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return (["status" => "error", "mensagem" => "Este domínio já está cadastrado"]);
            } else {
                return (["status" => "error", "mensagem" => "Erro ao salvar no banco: " . $e->getMessage()]);
            }
        }
    }

    public function listar() {
        $sites = $this->siteRepositorio->listar();
        return (["status" => "success", "sites" => $sites]);
    }

    public function remover($id) {
        $this->siteRepositorio->remover($id);
        return (["status" => "success", "mensagem" => "Site removido"]);
    }
}
?>
