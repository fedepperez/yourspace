<?php
require 'config.php';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $code     = trim($_POST['code'] ?? '');
    $newPass  = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($newPass !== $confirm) $errors[] = "Le password non coincidono.";
    elseif (strlen($newPass) < 6) $errors[] = "Password troppo corta.";
    else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND reset_code = ?");
        $stmt->execute([$username, $code]);
        if ($user = $stmt->fetch()) {
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            // Resetta la password e rimuove il codice usato
            $pdo->prepare("UPDATE users SET password_hash = ?, reset_code = NULL WHERE id = ?")->execute([$hash, $user['id']]);
            $success = "Password aggiornata! Ora puoi accedere.";
        } else {
            $errors[] = "Codice o username non validi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - YourSpace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="auth-box">
        <h1>YourSpace</h1>
        <h2>Imposta Nuova Password</h2>

        <?php if ($errors): ?>
            <div class="errors">
                <?php foreach ($errors as $e) echo "<p style='margin:0'>$e</p>"; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background:rgba(40, 167, 69, 0.2); border:1px solid #28a745; color:#8effa6; padding:15px; border-radius:6px; text-align:center; margin-bottom:15px;">
                <?= $success ?>
                <br><br>
                <a href="login.php" style="font-weight:bold; color:#fff; text-decoration:underline;">Vai al Login</a>
            </div>
        <?php else: ?>
            <p style="color:#aaa; text-align:center; font-size:0.9rem;">
                Inserisci il codice che hai ricevuto e scegli la tua nuova password.
            </p>

            <form method="post">
                <label>Username 
                    <input type="text" name="username" required placeholder="Il tuo username">
                </label>
                
                <label>Codice di recupero 
                    <input type="text" name="code" required placeholder="Es. 12345">
                </label>
                
                <label>Nuova Password 
                    <input type="password" name="new_password" required placeholder="Min. 6 caratteri">
                </label>
                
                <label>Conferma Password 
                    <input type="password" name="confirm_password" required placeholder="Ripeti password">
                </label>
                
                <button type="submit">Cambia Password</button>
            </form>
        <?php endif; ?>

        <?php if (!$success): ?>
            <p class="switch-auth"><a href="login.php">Annulla e torna al Login</a></p>
        <?php endif; ?>
    </div>
</body>
</html>