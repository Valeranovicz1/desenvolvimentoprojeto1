<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/models/Residencia.php';
require_once __DIR__ . '/models/Comodo.php';
require_once __DIR__ . '/models/Medicao.php';


use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Request;

Router::setDefaultNamespace('\App\Controllers');
define('APP_LOADED', true);

$baseDir = dirname($_SERVER['SCRIPT_NAME']);
if ($baseDir === '/' || $baseDir === '\\') {
    $baseDir = ''; 
}

Router::group(['prefix' => $baseDir], function () use ($baseDir) { 

    Router::get('/', function() {
        $residenciaModel = new Residencia();
        $residencias = $residenciaModel->getAll();
        require __DIR__ . '/views/residencia.php';
    });
    
    Router::get('/comodos', function() use ($baseDir) { 
        if (!isset($_SESSION['residencia_id'])) {

            \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/');
            exit; 
        }
        $comodoModel = new Comodo();
        $comodos = $comodoModel->getAllByResidencia($_SESSION['residencia_id']);
        $residencia_nome = $_SESSION['residencia_nome'];
        require __DIR__ . '/views/comodo.php';
    });
    
    Router::get('/medicoes', function() use ($baseDir) { 
        if (!isset($_SESSION['comodo_id'])) {

            \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/comodos');
            exit; 
        }
        $medicaoModel = new Medicao();
        $medicoes = $medicaoModel->getAllByComodo($_SESSION['comodo_id']);
        $residencia_nome = $_SESSION['residencia_nome'];
        $comodo_nome = $_SESSION['comodo_nome'];
        require __DIR__ . '/views/medicao.php';
    });

    Router::post('/residencias/add', function() use ($baseDir) {
        if (!empty($_POST['nomeResidencia'])) {
            $residenciaModel = new Residencia();
            $residenciaModel->create($_POST['nomeResidencia'], $_POST['endereco']);
        }
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/');
    });

    Router::get('/residencias/delete/{id}', function($id) use ($baseDir) {
        $residenciaModel = new Residencia();
        $residenciaModel->delete($id);
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/');
    });

    Router::get('/residencias/select/{id}', function($id) use ($baseDir) {
        $residenciaModel = new Residencia();
        $residencia = $residenciaModel->getById($id);
        if ($residencia) {
           
            $_SESSION['residencia_id'] = $residencia['Id'];
            $_SESSION['residencia_nome'] = $residencia['Nome'];
            unset($_SESSION['comodo_id']);
            unset($_SESSION['comodo_nome']);
        }
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/comodos');
    });

    Router::post('/comodos/add', function() use ($baseDir) {
        if (!empty($_POST['comodoNome']) && isset($_SESSION['residencia_id'])) {
            $comodoModel = new Comodo();
            $comodoModel->create($_POST['comodoNome'], $_SESSION['residencia_id']);
        }
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/comodos');
    });

    Router::get('/comodos/delete/{id}', function($id) use ($baseDir) {
        $comodoModel = new Comodo();
        $comodoModel->delete($id);
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/comodos');
    });

    Router::get('/comodos/select/{id}', function($id) use ($baseDir) {
        $comodoModel = new Comodo();
        $comodo = $comodoModel->getById($id);
        if ($comodo) {

            $_SESSION['comodo_id'] = $comodo['Id']; 
            $_SESSION['comodo_nome'] = $comodo['Nome'];
        }
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/medicoes');
    });


    Router::post('/medicoes/add', function() use ($baseDir) {
        if (isset($_SESSION['comodo_id'])) {
            $medicaoModel = new Medicao();
            $medicaoModel->create(
                $_POST['nivelSinal'],
                $_POST['velocidade'],
                $_POST['interferencia'],
                $_SESSION['comodo_id']
            );
        }
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/medicoes');
    });

    Router::get('/medicoes/delete/{id}', function($id) use ($baseDir) {
        $medicaoModel = new Medicao();
        $medicaoModel->delete($id);
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/medicoes');
    });

});

Router::error(function(Request $request, \Exception $exception) {
    if($exception instanceof \Pecee\SimpleRouter\Exceptions\NotFoundHttpException) {
        $request->setRewriteCallback(function() {
             return "<h1>Erro 404</h1><p>Página não encontrada.</p>";
        });
    }
});

Router::start();