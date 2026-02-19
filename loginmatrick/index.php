<?php
include 'db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['usuario'];
    if (isset($_POST['registrar'])) {
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
        $stmt->execute([$user, $pass]);
        $msg = "Registro exitoso";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$user]);
        $u = $stmt->fetch();
        if ($u && password_verify($_POST['password'], $u['password'])) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['username'] = $u['usuario'];
            header("Location: dashboard.php");
        } else { $msg = "Error en credenciales"; }
    }
}
?> 

<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="estilos.css"><title>Login</title></head>
<body>
    <div class="container">
        <h2>MatriockChat</h2>
        <?php if(isset($msg)) echo "<p>$msg</p>"; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" name="entrar">Entrar</button>
            <button type="submit" name="registrar" style="background:#4a90e2; margin-top:10px;">Registrarse</button>
        </form>
    </div>
</body>
</html>