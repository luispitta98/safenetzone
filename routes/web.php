<?php

require_once __DIR__ . '/../config/autoload.php';
$resposta = "";

$request_uri = $_SERVER['REQUEST_URI'];
$parts       = parse_url($request_uri);
$path        = $parts['path'];
$path        = ltrim($path, '/');
$urlSegments = explode('/', $path);
$urlSegments = array_filter($urlSegments, function ($value) {
    return !empty($value) || $value === 0;
});
$url = end($urlSegments);

$usuarioController      = new UsuarioController();
$siteController         = new SiteProibidoController();
$logController          = new LogController();
$configuracaoController = new ConfiguracaoController();
$servicoBloqueadoController = new ServicoBloqueadoController();
$bloqueioController = new BloqueioController();

try {
    switch ($url) {

        case 'verificar_sessao':
            $resposta = $usuarioController->verificarSessao();
            break;

        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $dados = lerJsonInput();
                $resposta = $usuarioController->login($dados['email'], $dados['senha']);
            } else {
                http_response_code(405);
                $resposta = ["status" => "error", "message" => "Método não permitido"];
            }
            break;

        case 'logout':
            $resposta = $usuarioController->logout();
            break;

        case 'usuarios':
            $resposta = gerenciar($usuarioController);
            break;

        case 'logs':
            $resposta = gerenciar($logController);
            break;

        case 'configuracao':
            $resposta = gerenciar($configuracaoController);
            break;

        case 'sites':
            $resposta = gerenciar($siteController);
            break;

        case 'servicos':
            $resposta = gerenciar($servicoBloqueadoController);
            break;

        case 'atualizarBloqueios':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $dados = lerJsonInput();
                $resposta = $bloqueioController->aplicarBloqueios($dados);
            }
            break;

        case 'relatorio_sites_mais_acessados':
            $resposta = $logController->relatorioSitesMaisAcessados();
            break;
        
        case 'relatorio_por_ip':
            $resposta = $logController->relatorioAcessosPorIp();
            break;
        
        case 'relatorio_conteudo_por_termo':
            $dados = lerJsonInput();
            $termo = $dados['termo'];
            $resposta = $logController->relatorioConteudoPorTermo($termo);
            break;
        
        case 'relatorio_por_hora':
            $resposta = $logController->relatorioAcessosPorHora();
            break;
        
        case 'relatorio_bloqueados':
            $resposta = $logController->relatorioAcessosBloqueados();
            break;
        
        case 'relatorio_por_dia':
            $resposta = $logController->relatorioAcessosPorDia();
            break;

        default:
            http_response_code(404);
            $resposta = ["status" => "error", "message" => "Rota não encontrada"];
    }
} catch (Exception $e) {
    http_response_code(500);
    $resposta = ["status" => "error", "message" => "Erro interno do servidor: " . $e->getMessage()];
}

http_response_code(200);
echoJson($resposta);

?>
