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
$stmt = $pdo->prepare("SELECT i.*, ii.image_path FROM Item i 
                       JOIN (SELECT item_id, MIN(image_path) as image_path FROM Item_Image GROUP BY item_id) ii 
                       ON i.item_id = ii.item_id 
                       WHERE i.buy_user = :user_id");
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
<header>
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
</header>
<div class="container">
    <h2>購入商品一覧</h2>
    <?php if (empty($items)): ?>
        <p>購入した商品はありません。</p>
    <?php else: ?>
        <div class="product-list">
            <?php foreach ($items as $item): ?>
                <div class="product-item">
                    <a href="itemdetails.php?item_id=<?= htmlspecialchars($item['item_id']) ?>">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['item_name']) ?>">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p>入札価格: ¥<?= number_format($item['item_price']) ?></p>
                            <p>カテゴリー: <?= htmlspecialchars($item['category']) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
<footer>
    <p>&copy; 2024 Tech Auction @Canva</p>
</footer>
</html>
