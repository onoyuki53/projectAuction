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
            header('Location: mypage.php');
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
<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
</head>
<body>
    <h2>ログインフォーム</h2>
    <?php if (!empty($err) && isset($err['login'])): ?>
        <p><?php echo $err['login']; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <div>
            <label for="user_name">ユーザー名:</label>
            <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name ?? '', ENT_QUOTES); ?>">
            <?php if (!empty($err['user_name'])): ?>
                <p><?php echo $err['user_name']; ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password">
            <?php if (!empty($err['password'])): ?>
                <p><?php echo $err['password']; ?></p>
            <?php endif; ?>
        </div>
        <div>
            <button type="submit">ログイン</button>
        </div>
    </form>
</body>
</html>
