<?php
// index.php (Corrigido para Case-Sensitivity e Redirecionamento)
session_start();

// 1. Carrega o Autoloader do Composer
require_once __DIR__ . '/vendor/autoload.php';

// 2. Carrega nossos Models
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/models/Residencia.php';
require_once __DIR__ . '/models/Comodo.php';
require_once __DIR__ . '/models/Medicao.php';

// 3. Importa as classes do Roteador
use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Request;

// =======================================================
// INÍCIO DO ROTEADOR PECEE
// =======================================================

Router::setDefaultNamespace('\App\Controllers');
define('APP_LOADED', true);

// --- CÁLCULO DO BASE PATH ---
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
if ($baseDir === '/' || $baseDir === '\\') {
    $baseDir = ''; 
}

// =======================================================
// INÍCIO DO GRUPO DE ROTAS
// =======================================================
Router::group(['prefix' => $baseDir], function () use ($baseDir) { // <-- $baseDir é passado para o grupo

    // -------------------------------------
    // ROTAS GET (Carregar Páginas)
    // -------------------------------------

    Router::get('/', function() {
        $residenciaModel = new Residencia();
        $residencias = $residenciaModel->getAll();
        require __DIR__ . '/views/residencia.php';
    });
    
    Router::get('/comodos', function() use ($baseDir) { // <-- $baseDir é passado aqui
        if (!isset($_SESSION['residencia_id'])) {
            // CORREÇÃO: Redireciona para a URL base correta
            \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/');
            exit; // Adiciona exit para parar a execução
        }
        $comodoModel = new Comodo();
        $comodos = $comodoModel->getAllByResidencia($_SESSION['residencia_id']);
        $residencia_nome = $_SESSION['residencia_nome'];
        require __DIR__ . '/views/comodo.php';
    });
    
    Router::get('/medicoes', function() use ($baseDir) { // <-- $baseDir é passado aqui
        if (!isset($_SESSION['comodo_id'])) {
            // CORREÇÃO: Redireciona para a URL correta
            \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/comodos');
            exit; // Adiciona exit para parar a execução
        }
        $medicaoModel = new Medicao();
        $medicoes = $medicaoModel->getAllByComodo($_SESSION['comodo_id']);
        $residencia_nome = $_SESSION['residencia_nome'];
        $comodo_nome = $_SESSION['comodo_nome'];
        require __DIR__ . '/views/medicao.php';
    });

    // -------------------------------------
    // ROTAS POST (Processar Formulários)
    // -------------------------------------

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
            // CORREÇÃO DE CASE SENSITIVITY: De 'id' para 'Id' e 'nome' para 'Nome'
            $_SESSION['residencia_id'] = $residencia['Id'];
            $_SESSION['residencia_nome'] = $residencia['Nome'];
            unset($_SESSION['comodo_id']);
            unset($_SESSION['comodo_nome']);
        }
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/comodos');
    });

    // --- AÇÕES DE CÔMODO ---
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
            // CORREÇÃO DE CASE SENSITIVITY (Preventiva): Assumindo 'Id' e 'Nome'
            $_SESSION['comodo_id'] = $comodo['Id']; 
            $_SESSION['comodo_nome'] = $comodo['Nome'];
        }
        \Pecee\SimpleRouter\SimpleRouter::response()->redirect($baseDir . '/medicoes');
    });

    // --- AÇÕES DE MEDIÇÃO ---
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

}); // FIM DO GRUPO DE ROTAS

// Rota de 404
Router::error(function(Request $request, \Exception $exception) {
    if($exception instanceof \Pecee\SimpleRouter\Exceptions\NotFoundHttpException) {
        $request->setRewriteCallback(function() {
             return "<h1>Erro 404</h1><p>Página não encontrada.</p>";
        });
    }
});

// Inicia o roteador
Router::start();