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
    } elseif (strlen($password) < 6) {
        $err['password'] = 'パスワードは6文字以上で入力してください。';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $err['password'] = 'パスワードは大文字、小文字、数字をそれぞれ含む必要があります。';
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
    
            // SQL文を準備
            $stmt = $pdo->prepare("INSERT INTO User (`user_id`, `mail`, `password`) VALUES (:user_id, :mail, :password)");
            
            //パスワードをハッシュ化するプログラム
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // パラメータをバインド
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':mail', $mail_address, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    
            // SQL文を実行
            $stmt->execute();
    
            // 次のステップへリダイレクト
            header('Location: registst2.php');
            exit;
        } catch (PDOException $e) {
            // エラー処理
            $err['db'] = 'データベースエラー: ' . $e->getMessage();
        }

        $_SESSION['user_id'] = $user_id;
        $_SESSION['password'] = $password;
        $_SESSION['mail_address'] = $mail_address;
        header('Location: registst2.php');
        exit;
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
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
     <div class="header">
	<div class="header_logo">
            <img src="./logo_square.png" alt="Logo">
        </div>
        <input type="text" id="k" name="k" class="form-control" placeholder="検索" required>
        <button type="submit" class="btn btn-primary btn-block">検索</button>
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
                <button type="submit" class="btn btn-primary btn-block">次へ</button>
            </form>
      </div>
</body>
</html>
