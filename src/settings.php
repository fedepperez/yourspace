<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Logica Elimina Account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    // Il DELETE CASCADE del database eliminerà post e like automaticamente
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
    session_destroy();
    header("Location: login.php?deleted=1");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Impostazioni - YourSpace</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="profile-page" style="--profile-color: <?= htmlspecialchars($currentUser['profile_color']) ?>;">
    <header class="topbar">
        <a href="home.php" class="topbar-logo">YourSpace</a>
        <nav>
            <span class="nav-username">Ciao, <?= htmlspecialchars($currentUser['username']) ?></span>
            <a href="home.php">Home</a>
            <a href="profile.php">Profilo</a>
            <a href="settings.php">Impostazioni</a>
            <a href="logout.php">Esci</a>
        </nav>
    </header>
    <main class="profile-shell">
        <section class="profile-main-card" style="grid-column: 1 / -1;">
            <h2>Impostazioni Account</h2>
            <div class="settings-section">
                <a href="forgot_password.php" class="settings-button">Cambia password</a>

                <form method="post" style="display:inline; margin-left:10px;" onsubmit="return confirm('SEI SICURO? Questa azione cancellerà tutto il tuo profilo e i tuoi post per sempre.');">
                    <input type="hidden" name="delete_account" value="1">
                    <button type="submit" class="danger-button">Elimina Profilo Definitivamente</button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>