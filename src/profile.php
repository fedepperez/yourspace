<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$formType = $_POST['form_type'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($formType === 'profile') {
        $bio = trim($_POST['bio'] ?? '');
        $color = $_POST['profile_color'] ?? '#222222';
        $pdo->prepare("UPDATE users SET bio = ?, profile_color = ? WHERE id = ?")->execute([$bio, $color, $user_id]);
    } elseif ($formType === 'new_post') {
        $content = trim($_POST['content'] ?? '');
        if ($content) {
            $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)")->execute([$user_id, $content]);
        }
    } elseif ($formType === 'delete_post') {
        $postId = (int)($_POST['post_id'] ?? 0);
        $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?")->execute([$postId, $user_id]);
    } elseif ($formType === 'toggle_like') {
        // Stessa logica della home, ma reindirizza a profile
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
    }
    header("Location: profile.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch();

// Post solo dell'utente
$sql = "SELECT p.*, COUNT(l.id) AS like_count,
               MAX(CASE WHEN l.user_id = :uid THEN 1 ELSE 0 END) AS liked_by_me
        FROM posts p
        LEFT JOIN likes l ON l.post_id = p.id
        WHERE p.user_id = :uid
        GROUP BY p.id ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['uid' => $user_id]);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Profilo - YourSpace</title>
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
        <section class="profile-main-card">
            <div class="profile-header-row">
                <div class="avatar-bubble">
                    <?= strtoupper(substr($currentUser['username'], 0, 1)) ?>
                </div>
                <div class="profile-heading">
                    <h2><?= htmlspecialchars($currentUser['username']) ?></h2>
                    <p class="profile-subtitle">Il tuo spazio personale.</p>
                    <div class="profile-meta">
                        <span class="profile-tag">Iscritto il <?= date('d/m/Y', strtotime($currentUser['created_at'])) ?></span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:1.5rem;">
                <h3>Bio</h3>
                <p class="bio"><?= $currentUser['bio'] ? nl2br(htmlspecialchars($currentUser['bio'])) : 'Nessuna bio.' ?></p>
            </div>

            <div style="margin-bottom:1.5rem;">
                <h3>Nuovo Post</h3>
                <form method="post" class="post-form">
                    <input type="hidden" name="form_type" value="new_post">
                    <textarea name="content" class="post-textarea" placeholder="Cosa stai pensando?" required></textarea>
                    <button type="submit" class="post-submit-btn">Pubblica</button>
                </form>
            </div>

            <h3>I tuoi Post</h3>
            <div class="posts-list">
                <?php if (!$posts): ?><p style="color:#aaa;">Non hai ancora postato nulla.</p><?php endif; ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
                        <form method="post" onsubmit="return confirm('Eliminare questo post?');">
                            <input type="hidden" name="form_type" value="delete_post">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <button type="submit" class="post-delete-btn" title="Elimina"><svg class="trash-icon" viewBox="0 0 24 24">
                                    <path d="M3 6h18M9 6V4h6v2m-7 4v8m4-8v8m4-8v8M5 6h14l-1 14H6L5 6z" stroke="currentColor" stroke-width="2" fill="none" />
                                </svg></button>
                        </form>
                        <p class="post-content"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <div class="post-footer-row">
                            <form method="post" class="like-form">
                                <input type="hidden" name="form_type" value="toggle_like">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button class="like-button <?= $post['liked_by_me'] ? 'liked' : '' ?>">❤</button>
                                <span class="like-count"><?= $post['like_count'] ?></span>
                            </form>
                            <span class="post-meta"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="profile-edit-card">
            <h3>Modifica Profilo</h3>
            <form method="post" class="profile-form">
                <input type="hidden" name="form_type" value="profile">
                <label>Bio <textarea name="bio" rows="4"><?= htmlspecialchars($currentUser['bio'] ?? '') ?></textarea></label>
                <label class="color-picker-inline">
                    Colore Tema
                    <input type="color" name="profile_color" value="<?= htmlspecialchars($currentUser['profile_color']) ?>">
                </label>
                <button type="submit" class="profile-save-btn">Salva Modifiche</button>
            </form>
        </section>
    </main>
</body>

</html>