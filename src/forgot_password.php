<?php
require 'config.php';

$step = 1; // 1 = Chiedi username, 2 = Mostra codice
$error = '';
$generatedCode = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    // Cerca se l'utente esiste
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Genera un codice casuale tra 10000 e 99999
        $generatedCode = rand(10000, 99999);

        // Salva questo codice nel database per questo utente
        $stmt = $pdo->prepare("UPDATE users SET reset_code = ? WHERE id = ?");
        $stmt->execute([$generatedCode, $user['id']]);

        // Passa allo step 2 (mostra il codice)
        $step = 2;
    } else {
        $error = "Username non trovato.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recupero Password</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-page">
    <div class="auth-box">
        <h1>YourSpace</h1>

        <?php if ($step === 1): ?>
            <h2>Password Dimenticata?</h2>
            <p style="color:#aaa; font-size:0.9rem; text-align:center;">
                Inserisci il tuo username. Ti daremo un codice per resettare la password.
            </p>

            <?php if ($error) echo "<div class='errors'>$error</div>"; ?>

            <form method="post">
                <label>Username
                    <input type="text" name="username" required placeholder="Il tuo username">
                </label>
                <button type="submit">Genera Codice</button>
            </form>
            <p class="switch-auth"><a href="login.php">Torna al login</a></p>

        <?php elseif ($step === 2): ?>
            <h2>Codice Generato!</h2>

            <div style="background: #222; border: 1px dashed #3aa4ff; padding: 15px; margin: 15px 0; border-radius: 8px; text-align: center;">
                <p style="margin:0; color:#aaa; font-size:0.8rem;">(Simulazione Email)</p>
                <p style="margin:5px 0; color:#fff;">Ciao <strong><?= htmlspecialchars($username) ?></strong>,</p>
                <p style="margin:0; color:#fff;">Il tuo codice di recupero è:</p>
                <h1 style="color:#3aa4ff; letter-spacing: 2px; font-size: 2.5rem; margin: 10px 0;">
                    <?= $generatedCode ?>
                </h1>
                <p style="margin:0; color:#aaa; font-size:0.8rem;">Copialo e usalo nella prossima pagina.</p>
            </div>

            <a href="reset_password.php" style="display:block; background:#3aa4ff; color:#000; text-align:center; padding:12px; border-radius:6px; font-weight:bold; text-decoration:none;">
                Vai a inserire il codice
            </a>
        <?php endif; ?>

    </div>
</body>

</html>