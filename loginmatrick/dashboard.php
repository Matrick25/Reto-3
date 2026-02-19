<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");

// Lógica de Envío
if (isset($_POST['enviar']) && !empty($_POST['mensaje'])) {
    $stmt = $pdo->prepare("INSERT INTO mensajes (remitente_id, destinatario_id, contenido) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['destinatario'], $_POST['mensaje']]);
}

// Obtener usuarios para el select
$usuarios = $pdo->query("SELECT id, usuario FROM usuarios WHERE id != ".$_SESSION['user_id'])->fetchAll();

// Obtener mensajes recibidos (incluyendo la fecha)
$stmt = $pdo->prepare("SELECT m.*, u.usuario FROM mensajes m JOIN usuarios u ON m.remitente_id = u.id WHERE destinatario_id = ? ORDER BY fecha DESC");
$stmt->execute([$_SESSION['user_id']]);
$mensajes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="estilos.css">
    <title>MatriockChat - Dashboard</title>
</head>
<body>
    <div class="container" style="width:550px">
        <h2>Hola, <?php echo $_SESSION['username']; ?></h2>

        <form method="POST">
            <label>Enviar a:</label>
            <select name="destinatario">
                <?php foreach($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= $u['usuario'] ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
            <button type="submit" name="enviar">Enviar Correo</button>
        </form>

        <div style="margin-top:30px">
            <h3>Bandeja de Entrada</h3>
            <?php if(empty($mensajes)) echo "<p>No tienes mensajes aún.</p>"; ?>
            <?php foreach($mensajes as $m): ?>
                <div class="mensaje-item">
                    <strong>De: <?= htmlspecialchars($m['usuario']) ?></strong>
                    <span style="float:right; font-size:0.8em; color:#bb86fc;"><?= $m['fecha'] ?></span>
                    <p style="margin-top:10px;"><?= nl2br(htmlspecialchars($m['contenido'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <br>
        <a href="index.php" style="color:#bb86fc; text-align:center; display:block;">Cerrar Sesión</a>
    </div>
</body>
</html>