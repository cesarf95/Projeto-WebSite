<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link class="icon" href="Imagens/automobile-with-wrench.png" type="image/png">
    <link rel="icon" href="imagens/login-credentials.png" type="image/png">
    <title> SGAI - Gestão de Ativos Industriais </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
    :root {
        --bg-primary: #f8f9fa;
        --card-bg: #ffffff;
        --text-primary: #212529;
        --text-secondary: #6c757d;
        --border-color: #dee2e6;
        --accent-color: #2b5c8f;
        /* Azul corporativo/industrial */
        --accent-hover: #1f446c;
        --success-neon: #00ff75;
    }

    body.dark-mode {
        --bg-primary: #0f1115;
        --card-bg: #1a1d24;
        --text-primary: #f8f9fa;
        --text-secondary: #a0aec0;
        --border-color: #2d3748;
        --accent-color: #3182ce;
        --accent-hover: #2b6cb0;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        font-family: 'Inter', sans-serif;
        transition: background-color 0.4s ease, color 0.4s ease;
        overflow-x: hidden;
    }

    /* --- BOTÃO SWITCH DE TEMA --- */
    .theme-switch-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    .theme-switch {
        font-size: 15px;
        position: relative;
        display: inline-block;
        width: 3.5em;
        height: 1.8em;
    }

    .theme-switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e0;
        transition: .3s;
        border-radius: 30px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 1.4em;
        width: 1.4em;
        border-radius: 50%;
        left: 0.2em;
        bottom: 0.2em;
        background-color: white;
        transition: .3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    body.dark-mode .slider {
        background-color: #4a5568;
    }

    input:checked+.slider {
        background-color: #48bb78;
    }

    input:checked+.slider:before {
        transform: translateX(1.7em);
    }

    /* --- LOADER OVERLAY --- */
    #loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 17, 21, 0.98);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.4s ease, visibility 0.4s ease;
        opacity: 0;
        visibility: hidden;
    }

    .terminal-loader {
        border: 1px solid var(--border-color);
        background-color: #1a1d24;
        color: #48bb78;
        font-family: "Courier New", monospace;
        padding: 2em;
        width: 18em;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        border-radius: 12px;
        text-align: left;
    }

    .banner-container {
        width: 100%;
        overflow: hidden;
        background-color: #0c1c30;
    }

    .banner-container img {
        width: 100%;
        height: 400px;
        max-height: none;
        display: block;
    }

    /* --- LAYOUT CENTRALIZADO DO CARD --- */
    .main-wrapper {
        display: flex;
        flex-direction: column; /* Alinha o card e as redes sociais verticalmente */
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 220px);
        padding: 40px 20px;
        gap: 30px; /* Espaço entre o formulário e os ícones */
    }

    .card-uiverse {
        transform-style: preserve-3d;
        transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        width: 380px;
        height: 580px;
        position: relative;
    }

    .form_front,
    .form_back {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 18px;
        position: absolute;
        backface-visibility: hidden;
        padding: 40px 35px;
        border-radius: 20px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        width: 100%;
        height: 100%;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }

    body.dark-mode .form_front,
    body.dark-mode .form_back {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
    }

    .form_back {
        transform: rotateY(-180deg);
    }

    .form_details {
        font-size: 26px;
        font-weight: 700;
        color: var(--text-primary);
        text-align: center;
        letter-spacing: -0.5px;
        margin-bottom: 5px;
    }

    /* --- INPUTS CORPORATIVOS E LIMPOS --- */
    .input {
        width: 100%;
        min-height: 46px;
        color: var(--text-primary);
        outline: none;
        transition: all 0.3s ease;
        padding: 0 15px;
        background-color: var(--bg-primary);
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 14px;
    }

    .input:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.15);
        background-color: var(--card-bg);
    }

    /* --- CONTAINER E ÍCONE DO OLHINHO --- */
    .password-container {
        position: relative;
        width: 100%;
    }

    .password-container .input {
        padding-right: 45px;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-secondary);
        font-size: 18px;
        z-index: 10;
        transition: color 0.2s;
    }

    .toggle-password:hover {
        color: var(--text-primary);
    }

    /* --- BOTÕES MODERNOS --- */
    .btn-uiverse {
        padding: 12px;
        cursor: pointer;
        background-color: var(--accent-color);
        border-radius: 8px;
        border: none;
        color: #ffffff;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 10px;
        box-shadow: 0 4px 6px rgba(49, 130, 206, 0.2);
    }

    .btn-uiverse:hover {
        background-color: var(--accent-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(49, 130, 206, 0.3);
    }

    /* --- TEXTOS DE ALTERNÂNCIA (SWITCH) --- */
    .switch {
        font-size: 13px;
        color: var(--text-secondary);
        text-align: center;
        margin-top: 10px;
    }

    .switch .signup_tog {
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        color: var(--accent-color);
        margin-left: 5px;
        transition: color 0.2s;
    }

    .switch .signup_tog:hover {
        text-decoration: underline;
    }

    #signup_toggle {
        display: none;
    }

    #signup_toggle:checked+.card-uiverse {
        transform: rotateY(-180deg);
    }

    /* --- ESTILOS DO COMPONENTE SOCIAL ICONS (Uiverse.io) --- */
    #SocailIcons {
        min-width: 200px;
        display: flex;
        justify-content: center;
        gap: 30px; /* Espaço entre os dois ícones */
        position: relative;
    }
    .icons {
        width: 50px;
        height: 50px;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
        outline: none;
        border: none;
        text-align: center;
        position: relative;
    }
    .iconName {
        position: absolute;
        top: -40px;
        font-size: 12px;
        color: #fff;
        transform: scale(0);
        border-radius: 3px;
        text-align: center;
        padding: 3px 8px;
        transition: transform 0.3s ease;
        white-space: nowrap;
        left: 50%;
        transform: translateX(-50%) scale(0);
    }

    .icons:hover .iconName {
        transform: translateX(-50%) scale(1);
    }

    .icons.linkedin:hover .iconName {
        background: #0274b3;
    }
    .icons.github:hover .iconName {
        background: #24292e;
    }
    
    .icon {
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        text-align: center;
        border-radius: 50%;
        position: relative;
        overflow: hidden;
        border: none;
        outline: none;
        transition: color 0.3s ease;
        color: #0c0c0c;
    }
    .icon:hover {
        color: #fff;
    }
    .icon::before {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 0;
        border-radius: 50%;
        transform: rotateX(360deg);
        transition: height 0.3s ease;
        z-index: -1;
    }

    .icon:hover::before {
        height: 100%;
    }
    .icon:hover {
        box-shadow: 5px 5px 10px #181717ce;
    }

    .icon.link::before {
        background: #0274b3;
    }

    .icon.git::before {
        background: #24292e;
    }
    </style>
    <link rel="stylesheet" href="css/animacoes.css">
</head>

<body>

    <div class="theme-switch-container">
        <label class="theme-switch">
            <input type="checkbox" id="toggle-fundo">
            <span class="slider"></span>
        </label>
    </div>

    <div id="loader-overlay">
        <div class="terminal-loader">
            <div style="display:flex; justify-content:space-between; margin-bottom: 15px; opacity:0.5; font-size:12px;">
                <span>SYSTEM_CHECK</span>
                <span>v2.1.0</span>
            </div>
            <div class="text">> Autenticando...</div>
        </div>
    </div>

    <div class="banner-container text-center">
        <img src="Imagens/banner final 4.jpg" alt="SGAI Banner">
    </div>

    <div class="main-wrapper">
        <input id="signup_toggle" type="checkbox">

        <div class="card-uiverse">
            <form class="form_front" id="form-login" name="login" action="login.php" method="POST">
                <div class="form_details">Acesso ao SGAI</div>
                <div style="text-align:center; color: var(--text-secondary); font-size:13px; margin-bottom:10px;">
                    Insira suas credenciais industriais
                </div>
                <input type="text" class="input" placeholder="Usuario ou Matricula necessarias..." name="login" autocomplete="off"
                    required>

                <div class="password-container">
                    <input type="password" class="input" id="campo-senha" placeholder="Senha de acesso..." name="senha"
                        required>
                    <i class="bi bi-eye toggle-password" id="btn-olhinho"></i>
                </div>

                <button type="submit" class="btn-uiverse">Entrar no Sistema</button>
                <span class="switch">Novo operador?
                    <label for="signup_toggle" class="signup_tog">Registrar Operador</label>
                </span>
            </form>

            <form class="form_back" id="form-cadastro" name="cadastro" action="cadastro_operadores.php" method="POST">
                <div class="form_details">Novo Registro</div>
                <input type="text" class="input" placeholder="Nome Completo" name="nome" required>
                <input type="text" class="input" placeholder="Gênero" name="genero" required>
                <input type="text" class="input" placeholder="Função / Cargo" name="funcao" required>
                <input type="date" class="input" name="data_entrada" required>
                <input type="text" class="input" placeholder="CPF (Apenas números)" name="cpf" required>
                <button type="submit" class="btn-uiverse">Registrar Operador</button>
                <span class="switch">Já possui registro?
                    <label for="signup_toggle" class="signup_tog">Fazer Login</label>
                </span>
            </form>
        </div>

        <div id="SocailIcons">
            <a href="https://www.linkedin.com/in/cesarf95" target="_blank" class="icons linkedin" rel="noopener noreferrer">
                <p class="iconName">Linkedin</p>
                <div class="icon link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                        <circle cx="4.983" cy="5.009" r="2.188" fill="currentColor"></circle>
                        <path d="M9.237 8.855v12.139h3.769v-6.003c0-1.584.298-3.118 2.262-3.118 1.937 0 1.961 1.811 1.961 3.218v5.904H21v-6.657c0-3.27-.704-5.783-4.526-5.783-1.835 0-3.065 1.007-3.568 1.96h-.051v-1.66H9.237zm-6.142 0H6.87v12.139H3.095z" fill="currentColor"></path>
                    </svg>
                </div>
            </a>
            
            <a href="https://github.com/cesarf95" target="_blank" class="icons github" rel="noopener noreferrer">
                <p class="iconName">GitHub</p>
                <div class="icon git">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.068.069-.068 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.113-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.579.688.481C19.137 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z" fill="currentColor"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
    // GERENCIAMENTO DE TEMA (DARK MODE)
    const botaoToggle = document.getElementById('toggle-fundo');
    const temaSalvo = localStorage.getItem('sgai-tema');

    if (temaSalvo === 'dark') {
        document.body.classList.add('dark-mode');
        if (botaoToggle) botaoToggle.checked = true;
    }

    if (botaoToggle) {
        botaoToggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('sgai-tema', 'dark');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('sgai-tema', 'light');
            }
        });
    }

    // LÓGICA DO OLHINHO (MOSTRAR / OCULTAR SENHA)
    const btnOlhinho = document.getElementById('btn-olhinho');
    const campoSenha = document.getElementById('campo-senha');

    if (btnOlhinho && campoSenha) {
        btnOlhinho.addEventListener('click', function() {
            if (campoSenha.type === 'password') {
                campoSenha.type = 'text';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            } else {
                campoSenha.type = 'password';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            }
        });
    }

    // CONTROLADORES DE SUBMIT COM LOADER
    const loader = document.getElementById('loader-overlay');
    const formLogin = document.getElementById('form-login');
    const formCadastro = document.getElementById('form-cadastro');

    if (formLogin) {
        formLogin.addEventListener('submit', (event) => {
            event.preventDefault();
            loader.style.visibility = 'visible';
            loader.style.opacity = '1';
            setTimeout(() => {
                formLogin.submit();
            }, 10000);
        });
    }

    if (formCadastro) {
        formCadastro.addEventListener('submit', (event) => {
            event.preventDefault();
            loader.style.visibility = 'visible';
            loader.style.opacity = '1';
            setTimeout(() => {
                formCadastro.submit();
            }, 2500);
        });
    }
    </script>

</body>

</html>