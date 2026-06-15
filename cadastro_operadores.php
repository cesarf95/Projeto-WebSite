<?php
include 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome         = trim($_POST['nome'] ?? '');
    $funcao       = trim($_POST['funcao'] ?? '');
    $genero       = trim($_POST['genero'] ?? '');
    $cpf          = trim($_POST['cpf'] ?? '');
    $data_entrada = trim($_POST['data_entrada'] ?? '');
    // Captura a senha vinda do formulário (caso decida adicioná-la no modal de operadores)


    // CRIPTOGRAFIA DA SENHA: Transforma o texto limpo em um hash seguro e irreversível


    try {
        // VERIFICA SE O OPERADOR JÁ EXISTE (Mantida a checagem pelos dados básicos)
        $sqlCheck = "SELECT COUNT(*) FROM operadores WHERE nome = :nome AND cpf = :cpf AND funcao = :funcao AND genero = :genero AND data_entrada = :data_entrada";
        $stmtCheck = $pdo->prepare($sqlCheck);

        $stmtCheck->bindParam(':nome', $nome);
        $stmtCheck->bindParam(':cpf', $cpf);
        $stmtCheck->bindParam(':funcao', $funcao);
        $stmtCheck->bindParam(':genero', $genero);
        $stmtCheck->bindParam(':data_entrada', $data_entrada);

        $stmtCheck->execute();

        if ($stmtCheck->fetchColumn() > 0) {
            echo "
            <script>
                alert('Operador já cadastrado no sistema!');
                history.back();
            </script>
            ";
            exit();
        } else {
            // CADASTRA NOVO OPERADOR (Incluída a coluna e o parâmetro da senha)
            $sqlInsert = "INSERT INTO operadores (nome, funcao, genero, data_entrada, cpf) VALUES (:nome, :funcao, :genero, :data_entrada, :cpf)";
            $stmtInsert = $pdo->prepare($sqlInsert);

            $stmtInsert->bindParam(':nome', $nome);
            $stmtInsert->bindParam(':funcao', $funcao);
            $stmtInsert->bindParam(':genero', $genero);
            $stmtInsert->bindParam(':data_entrada', $data_entrada);
            $stmtInsert->bindParam(':cpf', $cpf);
            // Faz o bind da senha já criptografada


            if ($stmtInsert->execute()) {
                echo "
                <script>
                    alert('Operador cadastrado com sucesso!');
                    window.location.href = 'operadores.php';
                </script>
                ";
                exit();
            } else {
                echo "
                <script>
                    alert('Erro ao cadastrar Operador!');
                    history.back();
                </script>
                ";
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Erro no Banco de Dados: " . $e->getMessage();
    }
}
