<?php

function gerenciar($controller) {
    $resposta = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dados = lerJsonInput();
        if (isset($dados['id']) && $dados['id'] > 0) {
            $resposta = $controller->editar($dados);
        } else {
            $resposta = $controller->adicionar($dados);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $resposta = $controller->obterComId($id);
        } else {
            if(isset( $_GET['limit'] ) ){
                $resposta = $controller->listar( $_GET['limit'] );
            } else {
                $resposta = $controller->listar();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $resposta = $controller->remover($id);
        } else {
            http_response_code(400);
            $resposta = ["status" => "error", "message" => "ID é obrigatório para deletar"];
        }
    } else {
        http_response_code(405);
        $resposta = ["status" => "error", "message" => "Método não permitido"];
    }

    return $resposta;
}

function lerJsonInput()
{
    $json  = file_get_contents('php://input');
    $dados = json_decode($json, true);
    if ($dados === null && json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array('mensagem' => 'Erro ao decodificar JSON.'));
        exit;
    }

    return $dados;
}

function echoJson($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

?>
