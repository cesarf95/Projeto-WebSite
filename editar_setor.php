<?php

session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

include 'conecta.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];

$sql = "UPDATE setores
        SET
            nome = :nome,
            descricao = :descricao
        WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':id', $id);
$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':descricao', $descricao);

$stmt->execute();

header('Location: setores.php');
exit;
