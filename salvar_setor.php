<?php

session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

include 'conecta.php';

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];

$sql = "INSERT INTO setores (
            nome,
            descricao
        )
        VALUES (
            :nome,
            :descricao
        )";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':descricao', $descricao);

$stmt->execute();

header('Location: setores.php');
exit;
