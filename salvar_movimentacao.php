<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

include 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: movimentacoes.php');
    exit;
}

$ativo_id = $_POST['ativo_id'] ?? '';
$setor_id = $_POST['setor_id'] ?? '';
$tipo_movimentacao = trim($_POST['tipo_movimentacao'] ?? '');
$data_movimentacao = $_POST['data_movimentacao'] ?? '';
$operador_id = !empty($_POST['operador_id']) ? $_POST['operador_id'] : null;
$observacao = trim($_POST['observacao'] ?? '');

if (empty($ativo_id) || empty($setor_id) || empty($tipo_movimentacao) || empty($data_movimentacao)) {
    echo "<script>alert('Por favor, preencha todos os campos obrigatórios.'); window.location.href='movimentacoes.php';</script>";
    exit;
}

try {
    $sql = "INSERT INTO movimentacoes (
                ativo_id,
                setor_id,
                operador_id,
                tipo_movimentacao,
                data_movimentacao,
                observacao
            ) VALUES (
                :ativo_id,
                :setor_id,
                :operador_id,
                :tipo_movimentacao,
                :data_movimentacao,
                :observacao
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ativo_id', $ativo_id);
    $stmt->bindParam(':setor_id', $setor_id);
    $stmt->bindParam(':operador_id', $operador_id);
    $stmt->bindParam(':tipo_movimentacao', $tipo_movimentacao);
    $stmt->bindParam(':data_movimentacao', $data_movimentacao);
    $stmt->bindParam(':observacao', $observacao);
    $stmt->execute();

    echo "<script>alert('Movimentação registrada com sucesso!'); window.location.href='movimentacoes.php';</script>";
    exit;
} catch (PDOException $e) {
    echo "<script>alert('Erro ao registrar movimentação: " . addslashes($e->getMessage()) . "'); window.location.href='movimentacoes.php';</script>";
    exit;
}
