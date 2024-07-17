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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../registst.css" rel="stylesheet">
</head>
<body>
  <div class="header">
    <div class="header_logo">
            <a href="../index.php">
                <img src="../img/logo_square.png" alt="Logo">
            </a>
    </div>
  </div>
  <div class="container">
    <?php if ($user && $userAdd): ?>
        <h2>ユーザー情報</h2><hr>
        <p><b>ユーザー名: </b><?= htmlspecialchars($user['user_id'], ENT_QUOTES) ?></p><hr>
        <p><b>メールアドレス: </b><?= htmlspecialchars($user['mail'], ENT_QUOTES) ?></p><hr>
        <p><b>住所: </b><?= htmlspecialchars($userAdd['address'], ENT_QUOTES) ?></p><hr>
        <p><b>電話番号: </b><?= htmlspecialchars($userAdd['phone'], ENT_QUOTES) ?></p><hr>
    <?php elseif ($user_id): ?>
        <p>ユーザー情報が見つかりません。</p>
    <?php else: ?>
        <p>ログインしてください。</p>
    <?php endif; ?>
    <a href="registchange.php" class="btn btn-primary">登録情報を変更する</a>
    <a href="../index.php" class="btn btn-secondary ml-2">ホームページ</a><br><br>
    <a href="../seller.php" class="btn btn-primary">出品一覧</a>
  </div>
</body>
<br>
<footer>
    <p>&copy; 2024 Tech Auction @Canva</p>
</footer>
</html> 
