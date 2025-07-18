<?php

$logPath = '/var/log/squid/access.log';

$ipsetName = 'liberados';

exec("ipset list $ipsetName", $output);
$ipsetContent = implode("\n", $output);

$logLines = @file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (!$logLines) exit;

$ipsDetectados = [];
foreach ($logLines as $linha) {
    if (preg_match('/\d+\.\d+\.\d+\.\d+/', $linha, $match)) {
        $ip = $match[0];
        if (!isset($ipsDetectados[$ip]) && strpos($ipsetContent, $ip) === false) {
            $ipsDetectados[$ip] = true;
        }
    }
}

// Adiciona os IPs ao ipset
foreach (array_keys($ipsDetectados) as $ip) {
    echo "Liberando $ip via ipset...\n";
    exec("sudo ipset add $ipsetName $ip timeout 600");
}
