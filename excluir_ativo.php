<?php

session_start();

if (!isset($_SESSION['nome'])) {
   header('Location: index.php');
   exit;
}

include 'conecta.php';



/* =========================================
   RECEBE ID
========================================= */

$id = $_POST['id'];



/* =========================================
   DELETE
========================================= */

$sql = "DELETE FROM ativos
        WHERE id = :id";

$stmt = $pdo->prepare($sql);



/* =========================================
   BIND
========================================= */

$stmt->bindValue(':id', $id);



/* =========================================
   EXECUTA
========================================= */

$stmt->execute();



/* =========================================
   REDIRECIONA
========================================= */

header('Location: ativos.php');
exit;
