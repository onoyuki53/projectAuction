<?php
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=auction;charset=utf8';
$user = 'user1';
$password = 'passwordA1!';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'データベース接続失敗: ' . $e->getMessage();
    exit;
}

session_start();

// クッキーからユーザーIDを取得
if (isset($_COOKIE['user_name'])) {
    $user_id = $_COOKIE['user_name'];

    // ユーザー情報を取得
    $stmt = $pdo->prepare('SELECT user_id, mail, password FROM User WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 住所と電話番号を取得
    $stmt = $pdo->prepare('SELECT address, phone FROM UserAdd WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $userAdd = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー情報</title>
</head>
<body>
    <?php if ($user && $userAdd): ?>
        <h2>ユーザー情報</h2>
        <p>ユーザー名: <?= htmlspecialchars($user['user_id'], ENT_QUOTES) ?></p>
        <p>メールアドレス: <?= htmlspecialchars($user['mail'], ENT_QUOTES) ?></p>
        <p>住所: <?= htmlspecialchars($userAdd['address'], ENT_QUOTES) ?></p>
        <p>電話番号: <?= htmlspecialchars($userAdd['phone'], ENT_QUOTES) ?></p>
    <?php elseif ($user_id): ?>
        <p>ユーザー情報が見つかりません。</p>
    <?php else: ?>
        <p>ログインしてください。</p>
    <?php endif; ?>
    <p><a href="registchange.php">登録情報を変更する</a></p>
</body>
</html> 
