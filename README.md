⚙️ Funcionalidades Principais

Autenticação
Login, verificação de sessão e logout via UsuarioController.

Gerenciamento de usuários
CRUD de usuários (nome, email, senha com hash) via interface web.

Configuração da rede Wi-Fi
Alteração de SSID e senha via ConfiguracaoController, atualização do hostapd.conf e reinicialização do serviço.

Controle de acesso
Bloqueio por serviço (WhatsApp, Discord, TikTok): manipula regras de DNS, Squid e IPTables via BlockUtils.

Sites proibidos: CADASTRO via web, bloqueio por DNS/Squid.

Aplicação dos bloqueios com criação de listas de bloqueio e reinício dos serviços.

Logs e relatórios
Captura de logs do Squid no banco.

Relatórios: sites mais acessados, por IP, por hora, bloqueados e termo sensível.

Ferramentas CLI

scripts/processar_logs.php: lê access.log, extrai MACs, insere no banco e limpa o log.

scripts/atualizar_ipset.php: atualiza ipsets com IPs permitidos dinamicamente.

🛠️ Requisitos

PHP ≥ 7.4 com PDO e extensões necessárias

MySQL

Squid, Dnsmasq, iptables, hostapd

RaspBarry Pi Model 8 - 32gb de disco.

🧩 Como usar

Faça login usando o usuário padrão: admin/admin.

Configure SSID/senha em Configuração da Rede (gera QR code).

Adicione/remova serviços ou sites proibidos em Controle de Acesso e clique em "Aplicar Bloqueios".

Confira logs detalhados em Relatórios.

Gerencie usuários em Usuários (se disponível).

🔧 Scripts CLI

processar_logs.php: popula o banco com registros do Squid. Executado idealmente, regularmente, via CRON.
atualizar_ipset.php: adiciona IPs detectados à lista "liberados" (útil para recuperação automática de bloqueios).

📄 Licença

Este projeto é open-source. Sinta-se livre para usá-lo, modificá-lo e contribuir!
