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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録フォーム - ステップ1</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../registst.css" rel="stylesheet">
</head>
<body>
     <div class="header">
	<div class="header_logo">
        <a href="./index.php">
            <img src="../img/logo_square.png" alt="Logo">
        </a>
        </div>
     </div>
	<div class="container">
            <h2 class="card-title text-center">ユーザー登録フォーム - ステップ1</h2>
            <?php if (!empty($err)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($err as $e): ?>
                        <p><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="user_id">ユーザーID:</label>
                    <input type="text" id="user_id" name="user_id" class="form-control" placeholder="koka_taro" required>
                </div>
                <div class="form-group">
                    <label for="password">パスワード:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_conf">パスワード（確認用）:</label>
                    <input type="password" id="password_conf" name="password_conf" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="mail_address">メールアドレス:</label>
                    <input type="email" id="mail_address" name="mail_address" class="form-control" placeholder="example@gmail.com" required>
                </div>
                <div>
                <button type="submit" class="btn btn-primary btn-block">次へ</button>
                </div>
            </form>
      </div>
</body>
<br>
<footer>
    <p>&copy; 2023 ブランドバンクオークション</p>
</footer>
</html>