<?php
require 'config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$username || !$email || !$password) $errors[] = "Compila tutti i campi.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email non valida.";
    elseif ($password !== $confirm) $errors[] = "Le password non coincidono.";
    else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Username o email già in uso.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati - YourSpace</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-page">
    <div class="auth-box">
        <h1>YourSpace</h1>
        <h2>Crea Profilo</h2>
        <?php if ($errors) foreach ($errors as $e) echo "<div class='errors'>$e</div>"; ?>
        <form method="post">
            <label>Username <input type="text" name="username" required></label>
            <label>Email <input type="email" name="email" required></label>
            <label>Password <input type="password" name="password" required></label>
            <label>Conferma <input type="password" name="confirm" required></label>
            <button type="submit">Registrati</button>
        </form>
        <p class="switch-auth">Hai già un account? <a href="login.php">Accedi</a></p>
    </div>
</body>

</html>