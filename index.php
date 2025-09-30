<?php

// ---- ARQUIVO: index.php ----
// Este é o Roteador ou Controlador Frontal (Front-Controller).
// Todo o tráfego do site passa por aqui.

// 1. Obter a URL que o usuário está tentando acessar.
// Usamos a variável $_SERVER['REQUEST_URI'] para isso.
$request_uri = $_SERVER['REQUEST_URI'];

// 2. Definir a pasta base do projeto.
// Isso é importante porque seu projeto não está na raiz do localhost.
$base_path = '/desenvolvimentoprojeto';

// 3. Remover a pasta base da URL para obter a rota "limpa".
// Ex: se a URL for /desenvolvimentoprojeto/sobre, a rota será /sobre.
$route = str_replace($base_path, '', $request_uri);

// 4. Limpar a rota para garantir que não tenhamos parâmetros GET (?id=1) ou barras extras.
$route = parse_url($route, PHP_URL_PATH);
$route = trim($route, '/');

// 5. Decidir qual arquivo da pasta "views" carregar com base na rota.
// Este é o roteamento em si.
switch ($route) {
    // Se a rota estiver vazia ("") ou for "home", carregamos a página inicial.
    case '':
    case 'home':
        require 'views/home.php';
        break;

    // --- EXEMPLO: Como adicionar outra página ---
    // Se a rota for "contato", carregamos a página de contato.
    // case 'contato':
    //     require 'views/contato.php';
    //     break;

    // Se a rota não corresponder a nenhuma das opções acima, mostramos um erro 404.
    default:
        http_response_code(404);
        require 'views/404.php';
        break;
}

?>