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
$err = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = filter_input(INPUT_POST, 'user_name');
    $password = filter_input(INPUT_POST, 'password');

    if (empty($user_name)) {
        $err['user_name'] = 'ユーザー名は入力必須です。';
    }
    if (empty($password)) {
        $err['password'] = 'パスワードは入力必須です。';
    }

    if (count($err) === 0) {
        // 正しい列名を使用していることを確認してください
        $stmt = $pdo->prepare('SELECT * FROM User WHERE user_id = :user_name');
        $stmt->bindValue(':user_name', $user_name);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // ログイン成功
            $_SESSION['login_user'] = $user['user_id'];

            // クッキーを設定（有効期限は30日）
            setcookie('user_name', $user['user_id'], time() + (30 * 24 * 60 * 60));
            header('Location: ./regist/mypage.php');
            exit;
        } else {
            $err['login'] = 'ログインに失敗しました。';
        }
    }
} else {
    // クッキーが存在する場合、ユーザー名フィールドを自動入力
    if (isset($_COOKIE['user_name'])) {
        $user_name = $_COOKIE['user_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
<div class="header">
    <div class="header_logo">
    <a href="./index.php">
            <img src="./img/logo_square.png" alt="Logo">
        </a>
    </div>
</div>
<div class="container">
    <h2>ログイン</h2>
    <?php if (!empty($err) && isset($err['login'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($err['login'], ENT_QUOTES) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="user_name">ユーザー名</label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="<?= htmlspecialchars($user_name ?? '', ENT_QUOTES) ?>" required>
            <?php if (!empty($err['user_name'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($err['user_name'], ENT_QUOTES) ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <?php if (!empty($err['password'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($err['password'], ENT_QUOTES) ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">ログイン</button>
            <a href="./regist/registst1.php" class="btn btn-secondary ml-2">新規登録</a>
        </div>
    </form>
</div>
</body>
</html>
