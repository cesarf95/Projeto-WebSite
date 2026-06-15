<?php
session_start(); // Inicia a sessão do PHP

include 'conecta.php'; // Inclui a conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($login === '' || $senha === '') {
        echo "<script>
        alert('Preencha todos os campos');
        window.location.href='index.php';
        </script>";
        exit;
    }

    try {
        // Busca o usuário pelo login informado
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE login = :login LIMIT 1");
        $stmt->bindValue(':login', $login);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se encontrou o usuário...
        if ($usuario && $login == $usuario['login']) {

            // 1. Verifica a senha principal
            $senhaPrincipalValida = password_verify($senha, $usuario['senha']);

            // Se a senha está salva em texto simples no banco, permite login e atualiza para hash seguro
            if (!$senhaPrincipalValida && $usuario['senha'] === $senha) {
                $senhaPrincipalValida = true;
                $senhaHashAtualizado = password_hash($senha, PASSWORD_DEFAULT);
                $atualizaSenha = $pdo->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
                $atualizaSenha->bindParam(':senha', $senhaHashAtualizado);
                $atualizaSenha->bindParam(':id', $usuario['id']);
                $atualizaSenha->execute();
            }

            // 2. Verifica a senha secundária
            $senhaSecundariaValida = false;
            if (!empty($usuario['senha_secundaria'])) {
                $senhaSecundariaValida = password_verify($senha, $usuario['senha_secundaria']);
                if (!$senhaSecundariaValida && $usuario['senha_secundaria'] === $senha) {
                    $senhaSecundariaValida = true;
                    $senhaSecundariaHashAtualizado = password_hash($senha, PASSWORD_DEFAULT);
                    $atualizaSenhaSecundaria = $pdo->prepare("UPDATE usuarios SET senha_secundaria = :senha_secundaria WHERE id = :id");
                    $atualizaSenhaSecundaria->bindParam(':senha_secundaria', $senhaSecundariaHashAtualizado);
                    $atualizaSenhaSecundaria->bindParam(':id', $usuario['id']);
                    $atualizaSenhaSecundaria->execute();
                }
            }

            // Se qualquer uma das senhas for válida
            if ($senhaPrincipalValida || $senhaSecundariaValida) {

                // Grava os dados na sessão
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['funcao'] = $usuario['funcao'];

                // Redireciona direto para a página principal do sistema
                header('Location: sgai.php');
                exit;
            }
        }

        // Se falhar o login ou a senha
        echo "<script>
        alert('Usuário ou senha inválidos');
        window.location.href='index.php';
        </script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>
        alert('Erro no sistema');
        window.location.href='index.php';
        </script>";
        exit;
    }
}
