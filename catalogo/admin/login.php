<?php
// /catalogo/admin/login.php
include '../api/conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT senha FROM administradores WHERE usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($senha, $admin['senha'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: painel.php');
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Login Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>body { padding-top: 50px; text-align: center; } .login-box { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }</style>
</head>
<body>
    <div class="login-box">
        <h2>Acesso Administrativo</h2>
        <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuário" required><br><br>
            <input type="password" name="senha" placeholder="Senha" required><br><br>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
</body>
</html>