<?php
require 'config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: home.php");
        exit;
    } else {
        $errors[] = "Username o password non validi.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - YourSpace</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-page">
    <div class="auth-box">
        <h1>YourSpace</h1>
        <h2>Accedi</h2>
        <?php if ($errors) foreach ($errors as $e) echo "<div class='errors'>$e</div>"; ?>
        <?php if (isset($_GET['registered'])) echo "<div style='color:#39d97f; margin-top:1rem; text-align:center;'>Registrazione ok. Accedi.</div>"; ?>
        <?php if (isset($_GET['deleted'])) echo "<div style='color:#ff6b6b; margin-top:1rem; text-align:center;'>Account eliminato.</div>"; ?>

        <form method="post">
            <label>Username <input type="text" name="username" required></label>
            <label>Password <input type="password" name="password" required></label>
            <button type="submit">Login</button>
        </form>
        <p class="switch-auth">Non hai un account? <a href="register.php">Registrati</a></p>
        <p class="switch-auth">Hai dimenticato la password? <a href="forgot_password.php">Recuperala qui</a></p>
    </div>
</body>

</html>