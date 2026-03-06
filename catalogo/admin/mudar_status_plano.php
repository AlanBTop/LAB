<?php
// /catalogo/admin/mudar_status_plano.php (Processador de Ações)
include '../api/conexao.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['acao'])) {
    $id = $_POST['id'];
    $acao = $_POST['acao'];
    $novo_plano = $_POST['novo_plano'] ?? null;

    try {
        if ($acao === 'aprovar') {
            $sql = "UPDATE prestadores SET status = 'aprovado', plano = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$novo_plano, $id]);
            $mensagem = "Cadastro APROVADO e Plano atualizado para " . strtoupper($novo_plano) . ".";
        } else if ($acao === 'reprovar') {
            $sql = "UPDATE prestadores SET status = 'reprovado' WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $mensagem = "Cadastro REPROVADO.";
        }
        
        $_SESSION['mensagem_admin'] = ['tipo' => 'success', 'texto' => $mensagem];

    } catch (PDOException $e) {
        $_SESSION['mensagem_admin'] = ['tipo' => 'error', 'texto' => 'Erro: ' . $e->getMessage()];
    }
}

header('Location: painel.php');
exit;