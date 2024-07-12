<?php
session_start();

$err = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = filter_input(INPUT_POST, 'user_id');
    $password = filter_input(INPUT_POST, 'password');
    $password_conf = filter_input(INPUT_POST, 'password_conf');
    $mail_address = filter_input(INPUT_POST, 'mail_address');

    if ($user_id === '') {
        $err['user_id'] = 'ユーザーIDは入力必須です。';
    }
    if ($password === '') {
        $err['password'] = 'パスワードは入力必須です。';
    }
    if ($password !== $password_conf) {
        $err['password_conf'] = 'パスワードが一致しません。';
    }
    if ($mail_address === '') {
        $err['mail_address'] = 'メールアドレスは入力必須です。';
    }

    if (empty($err)) {
        try {
            // データベース接続設定
            $pdo = new PDO('mysql:host=localhost;dbname=auction;charset=utf8mb4', 'user1', 'passwordA1!');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // ユーザーIDで検索するSQL文を準備
            $stmtCheckUserId = $pdo->prepare("SELECT * FROM `User` WHERE `user_id` = :user_id");
            $stmtCheckUserId->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmtCheckUserId->execute();

            // 結果を確認
            if ($stmtCheckUserId->fetch()) {
                $err['user_id'] = 'このユーザーIDは既に使用されています。';
            } else {
                // パスワードをハッシュ化
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // ユーザー情報を挿入するSQL文を準備
                $stmtInsertUser = $pdo->prepare("INSERT INTO `User` (`user_id`, `mail`, `password`) VALUES (:user_id, :mail, :password)");
                $stmtInsertUser->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $stmtInsertUser->bindParam(':mail', $mail_address, PDO::PARAM_STR);
                $stmtInsertUser->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

                // SQL文を実行
                $stmtInsertUser->execute();

                // セッションデータの設定
                $_SESSION['user_id'] = $user_id;
                $_SESSION['mail_address'] = $mail_address;
                header('Location: registst2.php');
                exit;
            }
        } catch (PDOException $e) {
            $err['db'] = 'データベースエラー: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録フォーム - ステップ1</title>
</head>
<body>
    <h2>ユーザー登録フォーム - ステップ1</h2>
    <?php if (!empty($err)): ?>
        <?php foreach ($err as $e): ?>
            <p style="color: red;"><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
    <form action="" method="post">
        <div>
            <label for="user_id">ユーザーID:</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="password_conf">パスワード（確認用）:</label>
            <input type="password" id="password_conf" name="password_conf" required>
        </div>
        <div>
            <label for="mail_address">メールアドレス:</label>
            <input type="email" id="mail_address" name="mail_address" value="<?php echo htmlspecialchars($mail_address ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div>
            <button type="submit">次へ</button>
        </div>
    </form>
</body>
</html>
