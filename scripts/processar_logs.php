<?php

require_once __DIR__ . '/../config/autoload.php';

$logFile = '/var/log/squid/access.log';
$controller = new LogController();

if (!file_exists($logFile)) {
    echo "Arquivo de log não encontrado: $logFile\n";
    exit(1);
}

$linhas = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (!$linhas) {
    echo "Nenhuma linha para processar.\n";
    exit(0);
}

function obterMacPorIp($ip) {
    $saida = '';
    $mac = null;

    if (!preg_match('/^192\.168\.10\./', $ip)) {
        return null;
    }

    $pingCmd = "sudo /bin/ping -c 1 -W 1 $ip";
    $pingOut = shell_exec("$pingCmd 2>&1");

    $arpCmd = "sudo /usr/sbin/arp -n $ip";
    $saida = shell_exec("$arpCmd 2>&1");

    if ($saida && preg_match('/([0-9a-f]{2}:){5}[0-9a-f]{2}/i', $saida, $matches)) {
        $mac = $matches[0];
    } else {
        $ipCmd = "sudo /sbin/ip neigh show $ip";
        $saidaIp = shell_exec("$ipCmd 2>&1");

        if ($saidaIp && preg_match('/([0-9a-f]{2}:){5}[0-9a-f]{2}/i', $saidaIp, $matches)) {
            $mac = $matches[0];
        }
    }

    return $mac;
}

$registros = [];

foreach ($linhas as $linha) {

    $partes = preg_split('/\s+/', $linha, 10);
    if (count($partes) < 9) continue;

    $ip = $partes[2];
    $mac = obterMacPorIp($ip);

    $partes = preg_split('/\s+/', $linha, 10);
    if (count($partes) < 9) continue;

    if (str_contains(strtolower($partes[6]), 'invalid-request')) continue;

    $bloqueio = null;

    if (str_contains($partes[3], 'DENIED')) {
        $bloqueio = 'Squid';
    } elseif (preg_match('/146\.112\.61\.10[4-9]/', $partes[8])) {
        $bloqueio = 'OpenDNS';
    }

    $registros[] = [
        'timestamp'     => (int) $partes[0],
        'tempo'         => (int) $partes[1],
        'ip'            => $partes[2],
        'codigo'        => $partes[3],
        'tamanho'       => (int) $partes[4],
        'metodo'        => $partes[5],
        'url'           => $partes[6],
        'usuario'       => $partes[7] !== '-' ? $partes[7] : null,
        'hierarquia'    => $partes[8],
        'conteudo_tipo' => $partes[9] ?? null,
        'mac'           => $mac ?? null,
        'bloqueio_tipo' => $bloqueio
    ];
}

$inseridos = 0;

foreach ($registros as $r) {
    if ($controller->adicionar($r)) {
        $inseridos++;
    }
}

echo "Inseridos $inseridos registros.\n";

// Limpa o arquivo (sem deletar)
file_put_contents($logFile, '');

?>
