<?php

class BlockUtils {

    private static $caminhoBase;
    private static $dirDnsmasq;
    private static $dirSquid;
    private static $arquivoDnsmasq;
    private static $arquivoSquid;
    private static $arquivoPalavras;


    private static $servicosSuportados = [
        'whatsapp' => [
            'dns'   => ["whatsapp.com", "web.whatsapp.com", "api.whatsapp.com", "cdn.whatsapp.net", "mmx-ds.cdn.whatsapp.net"],
            'squid' => [".whatsapp.com", ".web.whatsapp.com", ".api.whatsapp.com", ".cdn.whatsapp.net", ".fb.com", ".facebook.com"],
            'ips'   => ["31.13.64.0/18", "157.240.0.0/16"]
        ],
        'discord' => [
            'dns'   => ["discord.com", "discord.gg", "cdn.discordapp.com"],
            'squid' => [".discord.com", ".discord.gg", ".cdn.discordapp.com"],
            'ips'   => ["162.159.128.0/24", "162.159.129.0/24"]
        ],
        'tiktok' => [
            'dns'   => ["tiktok.com", "www.tiktok.com", "tiktokcdn.com"],
            'squid' => [".tiktok.com", ".www.tiktok.com", ".tiktokcdn.com"],
            'ips'   => ["161.117.0.0/16", "47.246.0.0/16"]
        ]
    ];

    private static function inicializar() {
        self::$caminhoBase = __DIR__ . '/../../arquivos-gerados-pelo-sistema';
        self::$dirDnsmasq     = self::$caminhoBase . '/dnsmasq-blocklists';
        self::$dirSquid       = self::$caminhoBase . '/squid-blocklists';

        self::criarDir(self::$dirDnsmasq);
        self::criarDir(self::$dirSquid);

        self::$arquivoDnsmasq = self::$dirDnsmasq . '/block-dnsmasq.conf';
        self::$arquivoSquid   = self::$dirSquid . '/block-squid.txt';
        self::$arquivoPalavras = self::$dirSquid . '/block-palavras.txt';
    }

    private static function criarDir($caminho) {
        if (!file_exists($caminho)) {
            mkdir($caminho, 0755, true);
        }
    }

    public static function aplicarTodos(array $servicosAtivos, array $palavras = []) {
        self::inicializar();

        $blocosDns = [];
        $blocosSquid = [];

        $blocosDns[] = "# Configurações globais";
        $blocosDns[] = "filter-AAAA";

        foreach (self::$servicosSuportados as $servico => $dados) {
            if (!empty($servicosAtivos[$servico])) {
                $blocosDns[] = "\n# $servico";
                foreach ($dados['dns'] as $d) {
                    $blocosDns[] = "address=/$d/0.0.0.0";
                    $blocosDns[] = "address=/$d/::";
                }

                $blocosSquid[] = "\n# $servico";
                $blocosSquid = array_merge($blocosSquid, $dados['squid']);

                foreach ($dados['ips'] as $faixa) {
                    shell_exec("sudo /sbin/iptables -C FORWARD -d $faixa -j DROP 2>/dev/null || sudo /sbin/iptables -I FORWARD 1 -d $faixa -j DROP");
                }
            } else {
                foreach ($dados['ips'] as $faixa) {
                    shell_exec("sudo /sbin/iptables -D FORWARD -d $faixa -j DROP 2>/dev/null");
                }
            }
        }

         // Gera ACL de palavras
        $blocosPalavras = [];
        if (!empty($palavras)) {
            foreach ($palavras as $termo) {
                $blocosPalavras[] = strtolower(trim($termo));
            }
        }

        shell_exec('sudo iptables-save | sudo tee /etc/iptables/rules.v4 > /dev/null');

        file_put_contents(self::$arquivoDnsmasq, implode("\n", $blocosDns) . "\n");
        file_put_contents(self::$arquivoSquid, implode("\n", $blocosSquid) . "\n");
        file_put_contents(self::$arquivoPalavras, implode("\n", $blocosPalavras) . "\n");

        shell_exec('sudo systemctl restart dnsmasq');
        shell_exec('sudo squid -k reconfigure');

        return ['success' => true];
    }
}