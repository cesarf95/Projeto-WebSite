<?php

session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

include 'conecta.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: setores.php');
    exit;
}

$sql = "DELETE FROM setores WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();

header('Location: setores.php');
exit;