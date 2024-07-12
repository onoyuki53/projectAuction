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
    <h1>登録完了</h1>
    <p>以下の情報で登録が完了しました。</p>
    <ul>

    </ul>
    <a href="login.php">ログインページへ</a>
    <br>
    <a href="index.php">ホームページへ</a>
    </div>
</body>
</html>
