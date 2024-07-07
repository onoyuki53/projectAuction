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
    <title>登録完了</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>登録完了</h1>
    <p>以下の情報で登録が完了しました。</p>
    <ul>

    </ul>
    <a href="login.php">ログインページへ</a>
    <br>
    <a href="index.php">ホームページへ</a>
</body>
</html>