buyitem.php

<?php
/* 購入商品表示 */

ini_set('display_errors', true);
error_reporting(E_ALL);

session_start();
require './database.php';

// クッキーが設定されているか確認
if (!isset($_COOKIE['user_name'])) {
    $logged_in = false;
    header("Location: ./login.php");
    exit();
} else {
    $logged_in = true;
    $user_id = $_COOKIE['user_name'];
}

$pdo = connect();

// 購入した商品の情報を取得
$stmt = $pdo->prepare("SELECT * FROM Item WHERE buy_user = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入商品一覧</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
     <div class="header">
	    <div class="header_logo">
        <a href="./index.php">
            <img src="./img/logo_square.png" alt="Logo">
        </a>
        </div>
        <div class="header_btn">
         <?php if ($logged_in): ?>
      	   <a href="./regist/mypage.php" class="btn btn-primary">マイページ</a>
      	   <a href="./logout.php" class="btn btn-secondary">ログアウト</a>
         <?php else: ?>
           <a href="./login.php" class="btn btn-primary">ログイン</a>
         <?php endif; ?>
         </div>
     </div>

<div class="container">
    <h1 class="card-title text-center">購入商品一覧</h1>

    <?php if (empty($items)): ?>
        <p>購入した商品はありません。</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>商品名</th>
                    <th>入札価格</th>
                    <th>カテゴリー</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($item['item_price'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
<footer>
    <p>&copy; 2023 ブランドバンクオークション</p>
</footer>
</html>