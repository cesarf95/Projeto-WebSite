<?php

session_start();

if (!isset($_SESSION['nome'])) {
   header('Location: index.php');
   exit;
}

include 'conecta.php';



/* =========================================
   RECEBE DADOS
========================================= */

$id         = $_POST['id'];
$nome       = trim($_POST['nome']);
$patrimonio = trim($_POST['patrimonio']);
$tipo       = trim($_POST['tipo']);
$fabricante = trim($_POST['fabricante']);
$modelo     = trim($_POST['modelo']);
$status     = trim($_POST['status']);
$setor_id   = $_POST['setor_id'];



/* =========================================
   UPDATE
========================================= */

$sql = "UPDATE ativos
        SET

            nome       = :nome,
            patrimonio = :patrimonio,
            tipo       = :tipo,
            fabricante = :fabricante,
            modelo     = :modelo,
            status     = :status,
            setor_id   = :setor_id

        WHERE id = :id";

$stmt = $pdo->prepare($sql);



/* =========================================
   BIND
========================================= */

$stmt->bindValue(':id', $id);
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

if ($stmt->execute()) {
   echo "<script>
            alert('Atualização realizada com sucesso!');
            window.location.href='ativos.php';
          </script>";
   exit; // Garante que o script PHP pare aqui
} else {
   echo "Erro ao atualizar o ativo.";
}
