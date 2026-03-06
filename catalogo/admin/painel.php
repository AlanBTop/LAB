<?php
// /catalogo/admin/painel.php
include '../api/conexao.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Lógica de busca
$stmt = $pdo->query("SELECT * FROM prestadores ORDER BY status, data_cadastro DESC");
$prestadores = $stmt->fetchAll();

// Lógica para logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Painel Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { padding: 20px; } 
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; font-size: 0.85rem; }
        .plano-tag-inicial { background-color: #f0f0f0; }
        .plano-tag-destaque { background-color: #fff3cd; color: #856404; }
        .plano-tag-profissional { background-color: #d4edda; color: #155724; font-weight: bold; }
        .status-pendente { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Gerenciamento de Prestadores</h2>
    <a href="?logout" class="btn btn-primary" style="float: right;">Sair</a>
    <a href="../cadastro.html" class="btn btn-primary" style="margin-right: 10px;">Novo Cadastro (Teste)</a>
    
    <?php 
    if (isset($_SESSION['mensagem_admin'])) {
        $msg = $_SESSION['mensagem_admin'];
        echo "<p style='color:".($msg['tipo'] == 'success' ? 'green' : 'red').";'>{$msg['texto']}</p>";
        unset($_SESSION['mensagem_admin']);
    }
    ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Serviço</th>
                <th>Bairro</th>
                <th>Status</th>
                <th>Plano Atual</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestadores as $p): ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['nome']); ?></td>
                <td><?php echo htmlspecialchars($p['tipo_servico']); ?></td>
                <td><?php echo htmlspecialchars($p['local_bairro']); ?></td>
                <td class="status-<?php echo $p['status']; ?>"><?php echo strtoupper($p['status']); ?></td>
                <td class="plano-tag-<?php echo $p['plano']; ?>"><?php echo strtoupper($p['plano']); ?></td>
                <td>
                    <form method="POST" action="mudar_status_plano.php" style="display:inline-flex; gap:5px;">
                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                        
                        <select name="novo_plano">
                            <option value="inicial" <?php echo $p['plano'] == 'inicial' ? 'selected' : ''; ?>>Inicial</option>
                            <option value="destaque" <?php echo $p['plano'] == 'destaque' ? 'selected' : ''; ?>>Destaque</option>
                            <option value="profissional" <?php echo $p['plano'] == 'profissional' ? 'selected' : ''; ?>>Profissional</option>
                        </select>
                        
                        <?php if ($p['status'] === 'pendente' || $p['status'] === 'reprovado'): ?>
                            <button type="submit" name="acao" value="aprovar" class="btn btn-success">Aprovar & Atualizar</button>
                        <?php endif; ?>
                        
                        <button type="submit" name="acao" value="reprovar" class="btn btn-danger">Reprovar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<div class="help-box" style="margin-top: 50px;">
    <h4>⚙️ Instruções de Gerenciamento</h4>
    <p>Use este painel para aprovar ou reprovar cadastros pendentes e para gerenciar o plano de cada prestador:</p>
    <ul>
        <li>**Cadastros Pendentes (PENDENTE):** Devem ser revisados. Use o menu de seleção para definir o plano e clique em **Aprovar & Atualizar**.</li>
        <li>**Mudança de Plano:** Você pode mudar o plano de qualquer prestador aprovado usando o menu de seleção e clicando em **Aprovar & Atualizar** (o status permanece aprovado).</li>
        <li>**Pré-visualização:** Clique no **nome** do prestador para visualizar como o perfil dele aparece no site.</li>
    </ul>
</div>