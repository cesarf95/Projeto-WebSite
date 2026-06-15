<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

$host = 'localhost'; $db = 'sgai'; $user = 'root'; $pass = '';
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

// ðŸ” RESOLUÃ‡ÃƒO DE ID DIRETA PELO NOME DO USUÃRIO
$usuario_logado = null;

// 1. Se jÃ¡ existir o ID na sessÃ£o, usamos ele diretamente
if (isset($_SESSION['usuario_id'])) {
    $usuario_logado = $_SESSION['usuario_id'];
} elseif (isset($_SESSION['id'])) {
    $usuario_logado = $_SESSION['id'];
} elseif (isset($_SESSION['id_usuario'])) {
    $usuario_logado = $_SESSION['id_usuario'];
} 

// 2. Se nÃ£o encontrou o ID, busca no banco apenas usando o 'nome' (evitando erro de coluna inexistente)
if (!$usuario_logado) {
    $stmtUserLogado = $pdo->prepare("SELECT id FROM usuarios WHERE nome = ? LIMIT 1");
    $stmtUserLogado->execute([$_SESSION['nome']]);
    $userEncontrado = $stmtUserLogado->fetch(PDO::FETCH_ASSOC);
    
    if ($userEncontrado) {
        $usuario_logado = $userEncontrado['id'];
    } else {
        // Fallback: Se nÃ£o achar o nome exato, pega o primeiro registro existente na tabela
        $stmtPrimeiro = $pdo->query("SELECT id FROM usuarios ORDER BY id ASC LIMIT 1");
        $primeiroUser = $stmtPrimeiro->fetch(PDO::FETCH_ASSOC);
        $usuario_logado = $primeiroUser ? $primeiroUser['id'] : 1;
    }
}

// Sincroniza o ID correto com a sessÃ£o para o arquivo 'mensagens_ajax.php' usar tambÃ©m
$_SESSION['usuario_id'] = $usuario_logado;

// Busca todos os outros usuÃ¡rios cadastrados para listar na barra lateral
$stmt = $pdo->prepare("SELECT id, nome, funcao FROM usuarios WHERE id != ? ORDER BY nome ASC");
$stmt->execute([$usuario_logado]);
$contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SGAI - Chat Interno</title>
    <link rel="icon" href="Imagens/comment.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2=family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    :root {
        --bg-primary: #f8f9fa;
        --card-bg: #ffffff;
        --text-primary: #212529;
        --border-color: #dee2e6;
        --accent-color: #2b5c8f;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-primary);
        color: var(--text-primary);
    }

    .chat-container {
        display: flex;
        height: calc(100vh - 60px);
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-top: 20px;
    }

    .sidebar-chat {
        width: 300px;
        border-right: 1px solid var(--border-color);
        background: #f1f3f5;
        display: flex;
        flex-direction: column;
    }

    .contato-item {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
        transition: 0.2s;
    }

    .contato-item:hover,
    .contato-item.active {
        background-color: #e9ecef;
    }

    .window-chat {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--card-bg);
    }

    .messages-box {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #fafafa;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .msg {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        font-size: 14px;
        position: relative;
        word-wrap: break-word;
    }

    .msg.enviada {
        background-color: var(--accent-color);
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 2px;
    }

    .msg.recebida {
        background-color: #e9ecef;
        color: #212529;
        align-self: flex-start;
        border-bottom-left-radius: 2px;
    }

    .msg-time {
        font-size: 10px;
        opacity: 0.7;
        display: block;
        text-align: right;
        margin-top: 5px;
    }

    .input-box {
        padding: 15px;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 10px;
        background: var(--card-bg);
    }
    </style>
<link rel="stylesheet" href="css/animacoes.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row bg-dark text-white p-3 align-items-center">
            <div class="col">
                <h5 class="m-0"><i class="bi bi-chat-left-text"></i> SGAI Comunicador Industrial</h5>
            </div>
            <div class="col text-end"><a href="sgai.php" class="btn btn-outline-light btn-sm">Voltar ao Painel</a>
            </div>
        </div>

        <div class="chat-container">
            <div class="sidebar-chat">
                <div class="p-3 bg-light font-weight-bold border-bottom">Usuários Cadastrados do Sistema</div>
                <div style="overflow-y: auto; flex: 1;">
                    <?php foreach($contatos as $c): ?>
                    <div class="contato-item"
                        onclick="selecionarContato(<?php echo $c['id']; ?>, '<?php echo addslashes($c['nome']); ?>')"
                        id="contato-<?php echo $c['id']; ?>">
                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($c['nome']); ?></div>
                        <div class="text-muted small"><?php echo htmlspecialchars($c['funcao']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="window-chat">
                <div class="p-3 bg-light border-bottom fw-bold" id="chat-header">
                    Selecione um colaborador para iniciar a comunicação
                </div>

                <div class="messages-box" id="messages-box">
                    <div class="text-center text-muted my-auto">Clique em alguém na lista ao lado para carregar o
                        historico de chamados.</div>
                </div>

                <form id="chat-form" class="input-box" style="display:none;">
                    <input type="text" id="input-mensagem" class="form-control"
                        placeholder="Digite sua mensagem corporativa..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary" style="background-color: var(--accent-color);">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    let contatoSelecionadoId = null;
    let usuarioLogadoId = <?php echo $usuario_logado; ?>;
    let intervaloBusca = null;

    function selecionarContato(id, nome) {
        contatoSelecionadoId = id;

        // Atualiza interface visual da lista lateral
        document.querySelectorAll('.contato-item').forEach(el => el.classList.remove('active'));
        document.getElementById('contato-' + id).classList.add('active');

        // Exibe o header e libera o formulÃ¡rio de envio
        document.getElementById('chat-header').innerHTML =
            `<i class="bi bi-person-circle me-2"></i> Canal Direto com: ${nome}`;
        document.getElementById('chat-form').style.display = 'flex';

        // Carrega mensagens imediatamente
        carregarMensagens();

        // Inicia o Polling automÃ¡tico (Verifica novas mensagens a cada 3 segundos)
        clearInterval(intervaloBusca);
        intervaloBusca = setInterval(carregarMensagens, 3000);
    }

    function carregarMensagens() {
        if (!contatoSelecionadoId) return;

        fetch(`mensagens_ajax.php?acao=buscar&com_id=${contatoSelecionadoId}`)
            .then(res => res.json())
            .then(mensagens => {
                const box = document.getElementById('messages-box');

                if (mensagens.length === 0) {
                    box.innerHTML =
                        '<div class="text-center text-muted my-auto">Nenhuma mensagem anterior. Digite algo abaixo para iniciar o chamado tÃ©cnico.</div>';
                    return;
                }

                let html = '';
                mensagens.forEach(m => {
                    const classeMsg = (m.remetente_id == usuarioLogadoId) ? 'enviada' : 'recebida';

                    // Trata a data para evitar quebras no JavaScript se o formato vier incompleto
                    let hora = "00:00";
                    if (m.data_envio) {
                        const t = m.data_envio.split(/[- :]/);
                        if (t.length >= 5) hora = `${t[3]}:${t[4]}`;
                    }

                    html += `
                    <div class="msg ${classeMsg}">
                        ${m.mensagem}
                        <span class="msg-time">${hora}</span>
                    </div>
                `;
                });

                const noFinal = box.scrollHeight - box.scrollTop <= box.clientHeight + 100;
                box.innerHTML = html;

                if (noFinal) {
                    box.scrollTop = box.scrollHeight;
                }
            })
            .catch(err => console.error("Erro ao carregar mensagens:", err));
    }

    // ENVIO DE MENSAGENS VIA FORMULÃRIO (SEM FAZER A PÃGINA RECARREGAR)
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('input-mensagem');
        const msgTexto = input.value.trim();
        if (!msgTexto || !contatoSelecionadoId) return;

        const formData = new FormData();
        formData.append('destinatario_id', contatoSelecionadoId);
        formData.append('mensagem', msgTexto);

        fetch('mensagens_ajax.php?acao=enviar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text()) // Captura em modo texto primeiro para pegar erros ocultos do PHP
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.status === 'sucesso') {
                        input.value = ''; // Limpa o campo de texto
                        carregarMensagens(); // Atualiza a conversa na hora
                    } else {
                        alert("Erro no servidor: " + data.msg);
                    }
                } catch (err) {
                    // Se cair aqui, o banco gerou um erro de SQL e imprimiu na tela
                    console.error("Resposta bruta do servidor:", text);
                    alert(
                        "Erro Critico de Banco de Dados! Veja os detalhes apertando F12 e indo na aba 'Console'."
                    );
                }
            })
            .catch(err => console.error("Erro na requisição Fetch:", err));
    });
    </script>

</body>

</html>
