<?php
session_start();

// エラーメッセージを格納する変数
$err = [];

// POSTリクエストが送信された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    
    // ユーザー名とパスワードが空でないかチェック
    if (empty($user_name)) {
        $err['user_name'] = 'ユーザー名は入力必須です。';
    }
    if (empty($password)) {
        $err['password'] = 'パスワードは入力必須です。';
    }
    
    // エラーがない場合
    if (count($err) === 0) {
        // データベース接続設定
        $dsn = 'mysql:host=localhost;dbname=auction;charset=utf8';
        $user = 'user1';
        $password_db = 'passwordA1!';
        try {
            $pdo = new PDO($dsn, $user, $password_db);
        } catch (PDOException $e) {
            echo 'データベース接続失敗: ' . $e->getMessage();
            exit;
        }
        
        // SQLインジェクションの脆弱性を含むクエリ
        $sql = "SELECT * FROM User WHERE user_id = '$user_name' AND password = '$password' OR 1=1";
        $stmt = $pdo->query($sql);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ユーザーが存在するか確認
        if ($user) {
            // ログイン成功
            $_SESSION['login_user'] = $user['user_id'];
            header('Location: ./mypage2.php');
            exit;
        } else {
            $err['login'] = 'ログインに失敗しました。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <style type="text/css">
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <form action="" method="post">
            <?php if (isset($err['login'])) : ?>
                <p class="error"><?php echo htmlspecialchars($err['login'], ENT_QUOTES); ?></p>
            <?php endif; ?>
            <p>
                <label for="user_name">ユーザー名</label>
                <input id="user_name" name="user_name" type="text" value="<?php echo htmlspecialchars($user_name ?? '', ENT_QUOTES); ?>" />
                <?php if (isset($err['user_name'])) : ?>
                    <p class="error"><?php echo htmlspecialchars($err['user_name'], ENT_QUOTES); ?></p>
                <?php endif; ?>
            </p>
            <p>
                <label for="password">パスワード</label>
                <input id="password" name="password" type="password" />
                <?php if (isset($err['password'])) : ?>
                    <p class="error"><?php echo htmlspecialchars($err['password'], ENT_QUOTES); ?></p>
                <?php endif; ?>
            </p>
            <p>
                <button type="submit">ログイン</button>
            </p>
        </form>
    </div>
</body>
</html>