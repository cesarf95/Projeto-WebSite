<?php
session_start();

// Garante que o cabeçalho seja JSON para evitar erros no console do navegador
header('Content-Type: application/json; charset=utf-8');

// Verifica se o usuário está logado
if (!isset($_SESSION['nome'])) {
    echo json_encode(['status' => 'erro', 'msg' => 'Não autorizado']);
    exit;
}

$host = 'localhost';
$db   = 'sgai';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'msg' => 'Falha na conexão: ' . $e->getMessage()]);
    exit;
}

// IMPORTANTE: Aqui usamos exatamente a variável que sincronizamos no chat.php
// Se não existir, buscamos a sessão, se não, deixamos um erro para evitar ID 1 fantasma
$usuario_logado_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_logado_id) {
    echo json_encode(['status' => 'erro', 'msg' => 'ID do usuário não identificado na sessão']);
    exit;
}

$acao = $_GET['acao'] ?? '';

// ==========================================
// AÇÃO: BUSCAR HISTÓRICO
// ==========================================
if ($acao === 'buscar') {
    $com_id = isset($_GET['com_id']) ? (int)$_GET['com_id'] : 0;

    if ($com_id === 0) {
        echo json_encode([]);
        exit;
    }

    $sql = "SELECT remetente_id, destinatario_id, mensagem, data_envio 
            FROM mensagens 
            WHERE (remetente_id = :eu AND destinatario_id = :outro)
               OR (remetente_id = :outro AND destinatario_id = :eu)
            ORDER BY data_envio ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':eu' => $usuario_logado_id, ':outro' => $com_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// ==========================================
// AÇÃO: GRAVAR MENSAGEM
// ==========================================
if ($acao === 'enviar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinatario_id = isset($_POST['destinatario_id']) ? (int)$_POST['destinatario_id'] : 0;
    $mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : '';

    if ($destinatario_id === 0 || empty($mensagem)) {
        echo json_encode(['status' => 'erro', 'msg' => 'Dados inválidos']);
        exit;
    }

    try {
        $sql = "INSERT INTO mensagens (remetente_id, destinatario_id, mensagem, data_envio) 
                VALUES (:remetente_id, :destinatario_id, :mensagem, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':remetente_id'    => $usuario_logado_id,
            ':destinatario_id' => $destinatario_id,
            ':mensagem'        => $mensagem
        ]);

        echo json_encode(['status' => 'sucesso']);
    } catch (PDOException $e) {
        // Se der erro, retorna o erro exato do SQL para você ver no console
        echo json_encode(['status' => 'erro', 'msg' => 'Erro ao salvar: ' . $e->getMessage()]);
    }
    exit;
}
