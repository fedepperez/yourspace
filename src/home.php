<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Gestione Like
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'toggle_like') {
    $postId = (int)($_POST['post_id'] ?? 0);
    if ($postId > 0) {
        $stmt = $pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$user_id, $postId]);
        if ($likeId = $stmt->fetchColumn()) {
            $pdo->prepare("DELETE FROM likes WHERE id = ?")->execute([$likeId]);
        } else {
            $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)")->execute([$user_id, $postId]);
        }
    }
    header("Location: home.php");
    exit;
}

// Utente corrente
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch();

// Tutti i post
$sql = "SELECT p.*, u.username, u.profile_color, 
               COUNT(l.id) AS like_count,
               MAX(CASE WHEN l.user_id = :uid THEN 1 ELSE 0 END) AS liked_by_me
        FROM posts p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN likes l ON l.post_id = p.id
        GROUP BY p.id ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['uid' => $user_id]);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - YourSpace</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="profile-page home-page">
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
        <section class="profile-main-card">
            <div class="profile-header-row">
                <div class="profile-heading">
                    <h2>Bacheca Globale</h2>
                    <p class="profile-subtitle">Scopri cosa dicono gli altri.</p>
                </div>
            </div>
            <div class="posts-list">
                <?php if (!$posts): ?>
                    <p style="color:#aaa;">Nessun post trovato.</p>
                    <?php else: foreach ($posts as $post):
                        $isMine = ($post['user_id'] == $user_id);
                        $initial = strtoupper(substr($post['username'], 0, 1));
                        $liked = (bool)$post['liked_by_me'];
                    ?>
                        <article class="post-card">
                            <div class="profile-header-row" style="margin-bottom:0.5rem; gap:0.8rem;">
                                <div class="avatar-bubble" style="width:40px; height:40px; font-size:1.1rem; border-width:1px;">
                                    <?= $initial ?>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars($post['username']) ?></strong>
                                    <?php if ($isMine): ?><span class="profile-tag" style="font-size:0.7rem;">Tu</span><?php endif; ?>
                                    <p class="post-meta"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></p>
                                </div>
                            </div>
                            <p class="post-content"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                            <div class="post-footer-row">
                                <form method="post" class="like-form">
                                    <input type="hidden" name="form_type" value="toggle_like">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button class="like-button <?= $liked ? 'liked' : '' ?>">❤</button>
                                    <span class="like-count"><?= $post['like_count'] ?></span>
                                </form>
                            </div>
                        </article>
                <?php endforeach;
                endif; ?>
            </div>
        </section>
        <section class="profile-edit-card">
            <h3>Scrivi qualcosa</h3>
            <p class="profile-edit-hint">Vuoi pubblicare un post? Vai al tuo profilo.</p>
            <a href="profile.php" class="profile-save-btn" style="text-decoration:none; display:inline-block;">Vai al Profilo</a>
        </section>
    </main>
</body>

</html>