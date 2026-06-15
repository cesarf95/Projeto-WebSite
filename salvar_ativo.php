<?php

session_start();

if (!isset($_SESSION['nome'])) {
   header('Location: index.php');
   exit;
}

include 'conecta.php';



/* =========================================
   RECEBENDO DADOS
========================================= */

$nome       = trim($_POST['nome']);
$patrimonio = trim($_POST['patrimonio']);
$tipo       = trim($_POST['tipo']);
$fabricante = trim($_POST['fabricante']);
$modelo     = trim($_POST['modelo']);
$status     = trim($_POST['status']);
$setor_id   = $_POST['setor_id'];



/* =========================================
   INSERT
========================================= */

$sql = "INSERT INTO ativos (

            nome,
            patrimonio,
            tipo,
            fabricante,
            modelo,
            status,
            setor_id

        ) VALUES (

            :nome,
            :patrimonio,
            :tipo,
            :fabricante,
            :modelo,
            :status,
            :setor_id

        )";

$stmt = $pdo->prepare($sql);



/* =========================================
   BIND
========================================= */

$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':patrimonio', $patrimonio);
$stmt->bindValue(':tipo', $tipo);
$stmt->bindValue(':fabricante', $fabricante);
$stmt->bindValue(':modelo', $modelo);
$stmt->bindValue(':status', $status);
$stmt->bindValue(':setor_id', $setor_id);



/* =========================================
   EXECUTA
========================================= */

$stmt->execute();



/* =========================================
   REDIRECIONA
========================================= */

header('Location: ativos.php');
exit;
