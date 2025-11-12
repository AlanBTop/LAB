<?php
// /admin/remover.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
    exit;
}

include '../api/conexao.php';
$id = $_POST['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID não fornecido.']);
    exit;
}
try {
    // Muda o status para 'reprovado' (mais seguro que deletar)
    $sql = "UPDATE prestadores SET status = 'reprovado' WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Prestador reprovado.']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Nenhum prestador encontrado.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro de BD: ' . $e->getMessage()]);
}