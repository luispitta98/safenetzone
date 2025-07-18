<?php

class ConfiguracaoController {
    private $configuracaoRepositorio;

    public function __construct() {
        $this->configuracaoRepositorio = new ConfiguracaoRepository();
    }

    public function adicionar($dados) {
        $permitidos = ['nome_rede', 'senha_rede'];
        $config = [];
    
        foreach ($dados as $campo => $valor) {
            if (in_array($campo, $permitidos)) {
                $this->configuracaoRepositorio->atualizarCampo($campo, $valor);
                $config[$campo] = $valor;
            }
        }
    
        // Após atualizar no banco, escreve no hostapd.conf
        try {
            $this->atualizarArquivoWifi($config);
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'mensagem' => 'Erro ao atualizar configuração da rede: ' . $e->getMessage()
            ];
        }
    
        return ['status' => 'success'];
    }

    public function listar() {
        $configuracao = $this->configuracaoRepositorio->listar();
        return (["status" => "success", "configuracao" => $configuracao]);
    }

    private function atualizarArquivoWifi($config) {
        $ssid = $config['nome_rede'] ?? "SafeZoneNet";
        $senha = $config['senha_rede'] ?? null;
    
        if (!$ssid || !$senha) {
            throw new Exception("Nome da rede ou senha não informados.");
        }
    
        $caminho = "/etc/hostapd/hostapd.conf";
        $conteudo = file_get_contents($caminho);
    
        if ($conteudo === false) {
            throw new Exception("Não foi possível ler o arquivo $caminho");
        }
    
        // Substitui os valores de ssid e wpa_passphrase
        $conteudo = preg_replace('/^ssid=.*/m', "ssid=$ssid", $conteudo);
        $conteudo = preg_replace('/^wpa_passphrase=.*/m', "wpa_passphrase=$senha", $conteudo);

        $tempPath = '/tmp/hostapd_temp.conf';
        if (file_put_contents($tempPath, $conteudo) === false) {
            throw new Exception("Não foi possível criar arquivo temporário");
        }

        $output = shell_exec('sudo cp ' . escapeshellarg($tempPath) . ' ' . escapeshellarg($caminho) . ' 2>&1');
        unlink($tempPath);

        if ($output !== null) {
            throw new Exception("Falha ao atualizar configuração: " . $output);
        }

        $output = shell_exec('sudo systemctl restart hostapd 2>&1');
        if ($output !== null) {
            throw new Exception("Falha ao reiniciar hostapd: " . $output);
        }
    }
}
?>
