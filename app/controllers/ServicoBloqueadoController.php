<?php

class ServicoBloqueadoController {
    private $servicoRepositorio;

    public function __construct() {
        $this->servicoRepositorio = new ServicoBloqueadoRepository();
    }

    public function listar() {
        $servicos = $this->servicoRepositorio->listar();
        return [
            'status' => 'success',
            'servicos' => $servicos->jsonSerialize()
        ];
    }

    public function adicionar($dados) {
        $permitidos = ['whatsapp', 'discord', 'tiktok'];
        $houveAtualizacao = false;
    
        foreach ($permitidos as $servico) {
            if (isset($dados[$servico])) {
                $ativo = !empty($dados[$servico]);
                $this->servicoRepositorio->atualizarServico($servico, $ativo);
                $houveAtualizacao = true;
            }
        }
    
        if (!$houveAtualizacao) {
            return ['status' => 'error', 'mensagem' => 'Nenhum serviço válido informado.'];
        }
    
        return ['status' => 'success'];
    }

}
