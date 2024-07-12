<?php
session_start();

// // 必要なセッション変数が存在するか確認
// if (!isset($_SESSION['user_id']) || !isset($_SESSION['password']) || !isset($_SESSION['mail_address']) || !isset($_SESSION['phone']) || !isset($_SESSION['address'])) {
//     header('Location: register.php');
//     exit;
// }

// // セッション変数からユーザー情報を変数に格納
// $user_id = $_SESSION['user_id'];
// $password = $_SESSION['password']; // 実際のアプリケーションではパスワードを直接表示しないように注意
// $mail_address = $_SESSION['mail_address'];
// $phone = $_SESSION['phone'];
// $address = $_SESSION['address'];

// セッションクリア
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録完了</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../registst.css" rel="stylesheet">
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
    <h1>登録完了</h1>
    <p>ログインをしてください。</p>
    <a href="../login.php" class="btn btn-primary">ログインページ</a>
    <a href="../index.php" class="btn btn-secondary">ホームページ</a>
    </div>
</body>
</html>