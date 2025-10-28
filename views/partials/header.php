<?php
// views/templates/header.php

// Proteção contra acesso direto
if (!defined('APP_LOADED')) {
    die('Acesso direto não permitido.');
}

$page = $page ?? 'residencia';
$cssFile = 'styleresidencia.css'; 

if ($page == 'comodo') {
    $cssFile = 'stylecomodo.css';
} elseif ($page == 'medicoes') {
    $cssFile = 'stylemedicao.css';
}

// Obtém a URL base (ex: /desenvolvimentoprojeto/)
$baseUrl = \Pecee\SimpleRouter\SimpleRouter::getUrl('/');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/<?php echo $cssFile; ?>"> 
    
    <title>Análise WiFi</title>
</head>
<body>