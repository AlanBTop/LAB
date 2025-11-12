<?php
// /admin/login.php
session_start();

if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true) {
    header("Location: painel.php");
    exit;
}
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $USUARIO_ADMIN = 'admin';
    $SENHA_ADMIN = 'admin123';

    if ($usuario === $USUARIO_ADMIN && $senha === $SENHA_ADMIN) {
        $_SESSION['admin_logado'] = true;
        $_SESSION['usuario'] = $usuario;
        header("Location: painel.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Catálogo Cáceres</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="POST">
            <h2>Painel Administrativo</h2>
            <p>Conecta Serviços Cáceres</p>
            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <?php if (!empty($erro)): ?>
                <p class="error-message"><?php echo $erro; ?></p>
            <?php endif; ?>
            <button type="submit" class="btn">Entrar</button>
        </form>
    </div>
</body>
</html>