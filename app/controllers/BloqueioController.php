<?php

class BloqueioController {

    public function aplicarBloqueios($dados) {
        $permitidos = ['whatsapp', 'discord', 'tiktok'];
        $estadoServicos = [];

        // Atualiza estado de serviços
        foreach ($permitidos as $servico) {
            $ativo = !empty($dados[$servico]);
            $estadoServicos[$servico] = $ativo;
        }

        // Gera os arquivos de bloqueio (agrupados)
        $resultado = BlockUtils::aplicarTodos($estadoServicos, $dados['sites'] ?? []);

        if (!$resultado['success']) {
            return [
                'status' => 'error',
                'mensagem' => $resultado['error'] ?? 'Erro ao aplicar bloqueios'
            ];
        }

        return ['status' => 'success'];
    }
}
