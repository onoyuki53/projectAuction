<?php
session_start();

// ログインしていない場合はログインページにリダイレクト
if (!isset($_SESSION['login_user'])) {
    header('Location: ./login2.php');
    exit;
}

// ログインユーザーの情報を取得
$user_id = $_SESSION['login_user'];

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

// ユーザー情報を取得
$stmt = $pdo->prepare('SELECT * FROM User WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo 'ユーザー情報の取得に失敗しました。';
    exit;
}

// user_nameキーが存在するか確認
if (!isset($user['user_name'])) {
    echo 'ユーザー名が設定されていません。';
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
</head>
<body>
    <h1>マイページ</h1>
    <p>ようこそ、<?php echo htmlspecialchars($user['user_name'], ENT_QUOTES); ?>さん</p>
    <p><a href="./logout.php">ログアウト</a></p>
</body>
</html>