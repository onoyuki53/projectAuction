<?php
// セッション開始
session_start();

// クッキーを無効にする
if (isset($_COOKIE['user_name'])) {
    // setcookie('user_name', '', time() - 3600, '/'); // クッキーを無効にするために過去の時間に設定
    setcookie('user_name', $user['user_id'], time() -3600);
}

// セッションを破棄
session_unset(); // セッション変数をすべて解除
session_destroy(); // セッションを破壊

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
     <div class="header">
	<div class="header_logo">
            <img src="./logo_square.png" alt="Logo">
        </div>
     </div>
    <div class="container">
        <h2 class="text-center">ログアウトしました</h2>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">ホームページ</a>
            <a href="login.php" class="btn btn-secondary">ログインページ</a>
        </div>
    </div>
</body>
</html>

