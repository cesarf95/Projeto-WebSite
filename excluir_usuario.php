<?php

// =========================================
// IMPORTA CONEXÃO COM O BANCO
// =========================================
include 'conecta.php';

// =========================================
// RECEBE O ID VIA URL (MÉTODO GET)
// =========================================
// Verifica se o ID foi enviado na URL para não dar erro
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // =========================================
    // COMANDO SQL DELETE
    // =========================================
    // Para deletar, precisamos APENAS do ID no WHERE
    $sql = "DELETE FROM usuarios WHERE id = :id";

    // Prepara o comando contra SQL Injection
    $stmt = $pdo->prepare($sql);

    // Liga o parâmetro :id à nossa variável $id
    $stmt->bindParam(':id', $id);

    // =========================================
    // EXECUTA A EXCLUSÃO
    // =========================================
    $stmt->execute();
}

// =========================================
// REDIRECIONA DE VOLTA PARA A LISTA
// =========================================
header('Location: usuarios.php'); //Ajustado, antes voltava para operadores.php. 

// Finaliza a execução do script
exit;
